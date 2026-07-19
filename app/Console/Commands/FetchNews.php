<?php

namespace App\Console\Commands;

use App\Services\RssFetcherService;
use App\Models\NewsSource;
use Illuminate\Console\Command;

class FetchNews extends Command
{
    protected $signature = 'news:fetch
                            {--source= : Belirli bir kaynaktan çek (slug)}
                            {--force : Zamanlama kontrolünü atla}';

    protected $description = 'RSS kaynaklarından haberleri otomatik çek';

    public function handle(RssFetcherService $fetcher): int
    {
        $this->info('🔄 Haber çekme işlemi başlatıldı...');
        $this->newLine();

        $sourceSlug = $this->option('source');
        $force = $this->option('force');

        if ($sourceSlug) {
            $source = NewsSource::where('slug', $sourceSlug)->first();

            if (!$source) {
                $this->error("Kaynak bulunamadı: {$sourceSlug}");
                return Command::FAILURE;
            }

            $this->info("📰 Kaynak: {$source->name}");
            $result = $fetcher->fetchFromSource($source);
            $this->displayResults($source->name, $result);

        } else {
            $sources = $force
                ? NewsSource::active()->get()
                : NewsSource::active()->get()->filter(fn($s) => $s->shouldFetch());

            if ($sources->isEmpty()) {
                $this->info('✅ Şu anda çekilecek kaynak yok.');
                return Command::SUCCESS;
            }

            $this->info("📋 {$sources->count()} kaynak işlenecek...");
            $this->newLine();

            foreach ($sources as $source) {
                $this->info("📰 İşleniyor: {$source->name}");
                $result = $fetcher->fetchFromSource($source);
                $this->displayResults($source->name, $result);
                $this->newLine();
            }
        }

        $this->newLine();
        $this->info('✅ Haber çekme işlemi tamamlandı.');

        return Command::SUCCESS;
    }

    protected function displayResults(string $sourceName, array $result): void
    {
        if (isset($result['error'])) {
            $this->error("  ❌ Hata: {$result['error']}");
            return;
        }

        $this->table(
            ['Metrik', 'Değer'],
            [
                ['Toplam', $result['total'] ?? 0],
                ['Yeni', $result['new'] ?? 0],
                ['Atlandı (duplike)', $result['skipped'] ?? 0],
                ['Hata', $result['errors'] ?? 0],
            ]
        );
    }
}
