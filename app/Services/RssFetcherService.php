<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsSource;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RssFetcherService
{
    protected array $fetchResults = [
        'total'     => 0,
        'new'       => 0,
        'skipped'   => 0,
        'errors'    => 0,
    ];

    /**
     * Tüm aktif kaynaklardan haberleri çek
     */
    public function fetchAll(): array
    {
        $sources = NewsSource::active()->get();
        $results = [];

        foreach ($sources as $source) {
            if ($source->shouldFetch()) {
                $results[$source->name] = $this->fetchFromSource($source);
            }
        }

        return $results;
    }

    /**
     * Belirli bir kaynaktan haberleri çek
     */
    public function fetchFromSource(NewsSource $source): array
    {
        $this->fetchResults = ['total' => 0, 'new' => 0, 'skipped' => 0, 'errors' => 0];

        try {
            $response = Http::withoutVerifying()->timeout(30)->get($source->url);

            if (!$response->successful()) {
                $source->increment('failed_fetches');
                Log::error("RSS Fetch Error: {$source->name} - HTTP {$response->status()}");
                return ['error' => "HTTP {$response->status()}"];
            }

            $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);

            if (!$xml) {
                $source->increment('failed_fetches');
                Log::error("RSS Parse Error: {$source->name} - Invalid XML");
                return ['error' => 'Invalid XML'];
            }

            // RSS 2.0, Atom veya RDF formatını belirle
            if (isset($xml->channel->item)) {
                $items = $xml->channel->item;
            } elseif (isset($xml->entry)) {
                $items = $xml->entry;
            } elseif (isset($xml->item)) {
                // RDF/RSS 1.0 formatı (DW gibi kaynaklar)
                $items = $xml->item;
            } else {
                return ['error' => 'Unknown feed format'];
            }

            foreach ($items as $item) {
                $this->processItem($item, $source, isset($xml->entry));
            }

            // Kaynak istatistiklerini güncelle
            $source->update([
                'last_fetched_at' => now(),
                'total_fetched'   => $source->total_fetched + $this->fetchResults['new'],
            ]);

        } catch (\Exception $e) {
            $source->increment('failed_fetches');
            Log::error("RSS Fetch Exception: {$source->name} - {$e->getMessage()}");
            $this->fetchResults['errors']++;
        }

        return $this->fetchResults;
    }

    /**
     * Tek bir feed öğesini işle
     */
    protected function processItem(\SimpleXMLElement $item, NewsSource $source, bool $isAtom = false): void
    {
        $this->fetchResults['total']++;

        try {
            // Başlık ve içerik çıkar
            $title       = $this->extractTitle($item, $isAtom);
            $link        = $this->extractLink($item, $isAtom);
            $description = $this->extractDescription($item, $isAtom);
            $content     = $this->extractContent($item, $isAtom);
            $pubDate     = $this->extractPubDate($item, $isAtom);
            $author      = $this->extractAuthor($item, $isAtom);
            $imageUrl    = $this->extractImage($item, $isAtom);
            $categories  = $this->extractCategories($item, $isAtom);

            if (empty($title) || empty($link)) {
                $this->fetchResults['skipped']++;
                return;
            }

            // Duplikasyon kontrolü
            $contentHash = News::generateContentHash($title, $content ?: $description);

            if (News::where('content_hash', $contentHash)->exists()) {
                $this->fetchResults['skipped']++;
                return;
            }

            if (News::where('source_url', $link)->exists()) {
                $this->fetchResults['skipped']++;
                return;
            }

            // Kategori eşleştirme (Çoklu - Otomatik Analizli)
            $categoryIds = $this->matchCategories($categories, $source, $title, $description);
            $primaryCategoryId = $categoryIds[0] ?? Category::first()?->id ?? 1;

            // Slug oluştur
            $slug = $this->generateUniqueSlug($title);

            // İçerik yoksa açıklamayı kullan
            if (empty($content)) {
                $content = $description;
            }

            // Görseli indir ve kaydet
            $imageId = null;
            if ($imageUrl) {
                $imageId = $this->downloadAndSaveImage($imageUrl, $slug);
            }

            // Manşet ataması (%15 şans, sadece görseli olanlar için)
            $isFeatured = false;
            if ($imageId && rand(1, 100) <= 15) {
                $isFeatured = true;
            }

            // Haberi kaydet
            $news = News::create([
                'title'               => Str::limit($title, 200),
                'description'         => Str::limit(strip_tags($description), 300),
                'content'             => $content,
                'category_id'         => $primaryCategoryId,
                'image_id'            => $imageId,
                'user_id'             => null, // Otomatik çekilen haberler
                'news_source_id'      => $source->id,
                'source_url'          => $link,
                'source_author'       => $author ? Str::limit($author, 200) : $source->name,
                'source_published_at' => $pubDate,
                'is_active'           => true,
                'is_breaking'         => false,
                'is_featured'         => $isFeatured,
                'status'              => $source->auto_publish ? 'published' : 'pending',
                'slug'                => $slug,
                'content_hash'        => $contentHash,
            ]);

            // Çoklu kategorileri bağla
            if (!empty($categoryIds)) {
                $news->categories()->sync($categoryIds);
            }

            // Kategoriden etiketler oluştur
            $this->autoTag($news, $title, $categories);

            $this->fetchResults['new']++;

        } catch (\Exception $e) {
            Log::warning("RSS Item Error: {$e->getMessage()}", [
                'source' => $source->name,
                'title'  => $title ?? 'unknown',
            ]);
            $this->fetchResults['errors']++;
        }
    }

    // ─── EXTRACT METHODS ──────────────────────────────

    protected function extractTitle(\SimpleXMLElement $item, bool $isAtom): string
    {
        return trim((string) $item->title);
    }

    protected function extractLink(\SimpleXMLElement $item, bool $isAtom): string
    {
        if ($isAtom) {
            // Atom formatı: <link href="..."/>
            foreach ($item->link as $link) {
                $attrs = $link->attributes();
                if (isset($attrs['href'])) {
                    return (string) $attrs['href'];
                }
            }
            return '';
        }
        return trim((string) $item->link);
    }

    protected function extractDescription(\SimpleXMLElement $item, bool $isAtom): string
    {
        if ($isAtom) {
            return trim((string) ($item->summary ?? $item->content ?? ''));
        }
        return trim((string) $item->description);
    }

    protected function extractContent(\SimpleXMLElement $item, bool $isAtom): string
    {
        if ($isAtom) {
            return trim((string) ($item->content ?? ''));
        }

        // RSS 2.0: content:encoded namespace
        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['content'])) {
            $contentNs = $item->children($namespaces['content']);
            if (isset($contentNs->encoded)) {
                return trim((string) $contentNs->encoded);
            }
        }

        return '';
    }

    protected function extractPubDate(\SimpleXMLElement $item, bool $isAtom): ?\DateTime
    {
        try {
            $dateStr = $isAtom
                ? (string) ($item->updated ?? $item->published ?? '')
                : (string) $item->pubDate;

            return $dateStr ? new \DateTime($dateStr) : null;
        } catch (\Exception) {
            return null;
        }
    }

    protected function extractAuthor(\SimpleXMLElement $item, bool $isAtom): ?string
    {
        if ($isAtom && isset($item->author->name)) {
            return (string) $item->author->name;
        }

        // dc:creator namespace
        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['dc'])) {
            $dc = $item->children($namespaces['dc']);
            if (isset($dc->creator)) {
                return (string) $dc->creator;
            }
        }

        return (string) ($item->author ?? null);
    }

    protected function extractImage(\SimpleXMLElement $item, bool $isAtom): ?string
    {
        // media:content namespace
        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content)) {
                $attrs = $media->content->attributes();
                if (isset($attrs['url'])) {
                    return (string) $attrs['url'];
                }
            }
            if (isset($media->thumbnail)) {
                $attrs = $media->thumbnail->attributes();
                if (isset($attrs['url'])) {
                    return (string) $attrs['url'];
                }
            }
        }

        // enclosure tag
        if (isset($item->enclosure)) {
            $attrs = $item->enclosure->attributes();
            if (isset($attrs['url']) && str_contains((string) ($attrs['type'] ?? ''), 'image')) {
                return (string) $attrs['url'];
            }
            if (isset($attrs['url'])) {
                return (string) $attrs['url'];
            }
        }

        // İçerikteki ilk img tag'ini bul
        $desc = (string) $item->description;
        if (preg_match('/<img[^>]+src=["\']([^"\']+)/i', $desc, $matches)) {
            return $matches[1];
        }

        // content:encoded namespace'ini kontrol et
        if (isset($namespaces['content'])) {
            $content = $item->children($namespaces['content']);
            if (isset($content->encoded)) {
                $encoded = (string) $content->encoded;
                if (preg_match('/<img[^>]+src=["\']([^"\']+)/i', $encoded, $matches)) {
                    return $matches[1];
                }
            }
        }

        return null;
    }

    protected function extractCategories(\SimpleXMLElement $item, bool $isAtom): array
    {
        $categories = [];

        if ($isAtom) {
            foreach ($item->category ?? [] as $cat) {
                $attrs = $cat->attributes();
                $categories[] = (string) ($attrs['term'] ?? $cat);
            }
        } else {
            foreach ($item->category ?? [] as $cat) {
                $categories[] = trim((string) $cat);
            }
        }

        return array_filter($categories);
    }

    // ─── HELPER METHODS ───────────────────────────────

    /**
     * Kaynak kategorisini ve içeriği analiz ederek yerel kategorilere eşleştir
     */
    protected function matchCategories(array $sourceCategories, NewsSource $source, string $title = '', string $description = ''): array
    {
        $matchedIds = [];
        $textToAnalyze = mb_strtolower($title . ' ' . strip_tags($description), 'UTF-8');
        // 1. Kaynak eşleştirme tablosuna bak
        if ($source->category_mappings) {
            foreach ($sourceCategories as $sourceCat) {
                $normalized = mb_strtolower(trim($sourceCat));
                if (isset($source->category_mappings[$normalized])) {
                    $matchedIds[] = $source->category_mappings[$normalized];
                }
            }
        }

        // 2. İsim benzerliğine göre eşleştir
        foreach ($sourceCategories as $sourceCat) {
            $normalized = mb_strtolower(trim($sourceCat));
            $category = Category::whereRaw('LOWER(name) LIKE ?', ["%{$normalized}%"])->first();
            if ($category) $matchedIds[] = $category->id;
        }

        // 3. Anahtar kelime eşleştirmesi (Güçlendirilmiş)
        $keywordMap = [
            'spor'      => ['sport', 'futbol', 'basketbol', 'voleybol', 'fenerbahçe', 'galatasaray', 'beşiktaş', 'trabzonspor', 'lig', 'maç', 'süper lig', 'şampiyon', 'milli takım'],
            'ekonomi'   => ['economy', 'finance', 'borsa', 'dolar', 'euro', 'piyasa', 'altın', 'faiz', 'enflasyon', 'tcmb', 'kredi', 'vergi', 'finans', 'zam', 'kripto', 'bitcoin'],
            'teknoloji' => ['technology', 'tech', 'bilişim', 'yazılım', 'yapay zeka', 'ai', 'telefon', 'apple', 'samsung', 'google', 'sosyal medya', 'oyun', 'uzay', 'nasa'],
            'bilim'     => ['science', 'bilim', 'araştırma', 'arkeoloji', 'keşif', 'uzay', 'evren', 'fosil', 'genetik', 'biyoloji', 'fizik', 'kimya'],
            'dünya'     => ['world', 'international', 'uluslararası', 'abd', 'rusya', 'ukrayna', 'avrupa', 'gazze', 'israil', 'biden', 'putin', 'küresel'],
            'türkiye'   => ['türkiye', 'ankara', 'istanbul', 'izmir', 'chp', 'ak parti', 'tbmm', 'bakan', 'cumhurbaşkanı', 'sondakika'],
            'sağlık'    => ['health', 'sağlık', 'tıp', 'hastalık', 'doktor', 'hastane', 'kanser', 'kalp', 'beslenme', 'diyet', 'virüs', 'salgın'],
            'yaşam'     => ['yaşam', 'hayat', 'eğitim', 'okul', 'öğrenci', 'üniversite', 'insan', 'toplum'],
            'magazin'   => ['magazin', 'ünlüler', 'dizi', 'oyuncu', 'şarkıcı', 'konser', 'televizyon', 'tv'],
            'otomobil'  => ['otomobil', 'araba', 'motor', 'araç', 'tog', 'togg', 'tesla', 'elektrikli', 'otomotiv', 'suv', 'sedan'],
            'kültür'    => ['culture', 'sanat', 'sinema', 'müzik', 'tiyatro', 'kitap', 'yazar', 'tarih'],
            'gündem'    => ['gündem', 'siyaset', 'politika', 'politics', 'haber'],
        ];

        // Metin içerisinden anahtar kelime eşleştirme (Otomatik analiz)
        foreach ($keywordMap as $catSlug => $keywords) {
            foreach ($keywords as $keyword) {
                // Kelime bütünlüğü için boşluklu veya noktalama işaretli arama
                if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/iu', $textToAnalyze)) {
                    $category = Category::where('slug', $catSlug)->first();
                    if ($category) {
                        $matchedIds[] = $category->id;
                    }
                    break; // Bir kategoriden bir kelime bulmak yeterli
                }
            }
        }

        // Slug kullanarak eşleştirme (daha kesin sonuç)
        foreach ($sourceCategories as $sourceCat) {
            $normalized = mb_strtolower(trim($sourceCat), 'UTF-8');
            foreach ($keywordMap as $catSlug => $keywords) {
                if ($normalized === $catSlug || in_array($normalized, $keywords)) {
                    $category = Category::where('slug', $catSlug)->first();
                    if ($category) $matchedIds[] = $category->id;
                }
                
                // Kısmi eşleşme
                foreach ($keywords as $keyword) {
                    if (str_contains($normalized, $keyword)) {
                        $category = Category::where('slug', $catSlug)->first();
                        if ($category) $matchedIds[] = $category->id;
                    }
                }
            }
        }

        if (empty($matchedIds) && $source->default_category_id) {
            $matchedIds[] = $source->default_category_id;
        }

        // 5. Hiç bulunamadıysa ilk mevcut kategoriyi döndür
        if (empty($matchedIds)) {
            $first = Category::first();
            if ($first) $matchedIds[] = $first->id;
        }

        return array_unique($matchedIds);
    }

    /**
     * Benzersiz slug oluştur
     */
    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'haber-' . uniqid();
        }

        $originalSlug = $slug;
        $counter = 2;

        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Görseli indir ve kaydet
     */
    protected function downloadAndSaveImage(string $url, string $slug): ?int
    {
        try {
            // Check if URL is valid
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return null;
            }

            $response = Http::withoutVerifying()->timeout(15)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $contentType = strtolower($response->header('Content-Type') ?? '');
            
            // Sıkı MIME türü kontrolü
            $validMimeTypes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp'
            ];

            $extension = null;
            foreach ($validMimeTypes as $mime => $ext) {
                if (str_contains($contentType, $mime)) {
                    $extension = $ext;
                    break;
                }
            }

            // Eğer desteklenmeyen bir formata sahipse (.svg, .html vb.) atla
            if (!$extension) {
                Log::warning("Unsupported image MIME type: {$contentType} for URL: {$url}");
                return null;
            }

            $fileName = Str::slug($slug) . '-' . uniqid() . '.' . $extension;
            $path = 'news-images/' . $fileName;

            Storage::disk('public')->put($path, $response->body());

            $image = Image::create([
                'path' => $path,
                'name' => $fileName,
            ]);

            return $image->id;
        } catch (\Exception $e) {
            Log::error("Image Download Error: " . $e->getMessage(), ['url' => $url, 'slug' => $slug]);
            return null;
        }
    }

    /**
     * Otomatik etiketleme
     */
    protected function autoTag(News $news, string $title, array $categories): void
    {
        $tags = [];

        // Kategorileri etiket olarak ekle
        foreach ($categories as $cat) {
            $cat = trim($cat);
            if (!empty($cat) && mb_strlen($cat) <= 50) {
                $tags[] = $cat;
            }
        }

        // Başlıktan anahtar kelimeleri çıkar (basit yaklaşım)
        $commonWords = ['ve', 'bir', 'bu', 'ile', 'için', 'olan', 'den', 'dan', 'de', 'da', 'mi', 'mı'];
        $words = array_filter(explode(' ', $title), function ($word) use ($commonWords) {
            return mb_strlen($word) > 3 && !in_array(mb_strtolower($word), $commonWords);
        });

        // İlk 3 anlamlı kelimeyi etiket yap
        $titleTags = array_slice(array_values($words), 0, 3);
        $tags = array_merge($tags, $titleTags);

        // Etiketleri kaydet ve habere bağla
        $tagIds = [];
        foreach (array_unique(array_slice($tags, 0, 5)) as $tagName) {
            $tag = \App\Models\Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tag->increment('usage_count');
            $tagIds[] = $tag->id;
        }

        if (!empty($tagIds)) {
            $news->tags()->sync($tagIds);
        }
    }
}
