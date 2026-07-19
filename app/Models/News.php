<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'description',
        'content',
        'category_id',
        'image_id',
        'user_id',
        'news_source_id',
        'source_url',
        'source_author',
        'source_published_at',
        'reading_time',
        'is_active',
        'is_breaking',
        'is_featured',
        'is_editor_pick',
        'status',
        'views',
        'slug',
        'meta_title',
        'meta_description',
        'content_hash',
    ];

    protected $casts = [
        'is_active'            => 'boolean',
        'is_breaking'          => 'boolean',
        'is_featured'          => 'boolean',
        'is_editor_pick'       => 'boolean',
        'views'                => 'integer',
        'reading_time'         => 'integer',
        'image_id'             => 'integer',
        'category_id'          => 'integer',
        'user_id'              => 'integer',
        'news_source_id'       => 'integer',
        'source_published_at'  => 'datetime',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
        'deleted_at'           => 'datetime',
    ];

    // ─── İLİŞKİLER ────────────────────────────────────

    // HABER > KATEGORİ ilişkisi
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // HABER > ÇOKLU KATEGORİ ilişkisi
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_news')
                    ->withTimestamps();
    }

    // HABER > YAZAN KULLANICI ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // HABER > GÖRSEL ilişkisi
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    // HABER > YORUMLAR ilişkisi
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // HABER > ETİKETLER ilişkisi
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'news_tag')
                    ->withTimestamps();
    }

    // HABER > KAYNAK ilişkisi
    public function newsSource()
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id');
    }

    // ─── SCOPE'LAR ─────────────────────────────────────

    // Scope: yalnızca aktif haberleri getir
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: yayınlanmış haberler
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_active', true);
    }

    // Scope: son dakika haberleri
    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true)->published();
    }

    // Scope: manşet haberleri
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->published();
    }

    // Scope: editör seçimleri
    public function scopeEditorPicks($query)
    {
        return $query->where('is_editor_pick', true)->published();
    }

    // Scope: en çok okunanlar
    public function scopeMostRead($query, int $limit = 10)
    {
        return $query->published()->orderByDesc('views')->limit($limit);
    }

    // Scope: en çok yorumlananlar
    public function scopeMostCommented($query, int $limit = 10)
    {
        return $query->published()
                     ->withCount('comments')
                     ->orderByDesc('comments_count')
                     ->limit($limit);
    }

    // ─── YARDIMCI METODLAR ─────────────────────────────

    /**
     * İçerikten tahmini okuma süresini hesapla (dakika)
     */
    public static function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingTime = max(1, (int) ceil($wordCount / 200)); // Ortalama 200 kelime/dakika
        return $readingTime;
    }

    /**
     * İçerik hash'i oluştur (duplikasyon kontrolü)
     */
    public static function generateContentHash(string $title, string $content): string
    {
        $normalized = mb_strtolower(trim($title) . '|' . trim(Str::limit(strip_tags($content), 500, '')));
        return hash('sha256', $normalized);
    }

    /**
     * Benzer haberleri bul
     */
    public function relatedNews(int $limit = 5)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('category_id', $this->category_id)
                      ->orWhereHas('tags', function ($q) {
                          $q->whereIn('tags.id', $this->tags->pluck('id'));
                      });
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Yayın tarihini insan dostu formatta döndür
     */
    public function getPublishedDateAttribute(): string
    {
        $date = $this->source_published_at ?? $this->created_at;
        $diff = $date->diffInMinutes(now());

        return match (true) {
            $diff < 1       => 'Az önce',
            $diff < 60      => $diff . ' dakika önce',
            $diff < 1440    => $date->diffInHours(now()) . ' saat önce',
            $diff < 10080   => $date->diffInDays(now()) . ' gün önce',
            default         => $date->format('d.m.Y H:i'),
        };
    }

    /**
     * Görsel URL'sini döndür (yedek görsel dahil)
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && $this->image->path) {
            return asset('storage/' . $this->image->path);
        }

        // Yedek görsel: Yerel SVG placeholder
        return asset('images/placeholder.svg');
    }

    // ─── BOOT ──────────────────────────────────────────

    protected static function booted()
    {
        // Kaydetmeden önce okuma süresi ve hash hesapla
        static::saving(function ($news) {
            if ($news->isDirty('content') || !$news->reading_time) {
                $news->reading_time = static::calculateReadingTime($news->content ?? '');
            }

            if ($news->isDirty(['title', 'content']) || !$news->content_hash) {
                $news->content_hash = static::generateContentHash(
                    $news->title ?? '',
                    $news->content ?? ''
                );
            }
        });
    }
}
