<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MarketDataService
{
    /**
     * Get market data (USD, EUR, GOLD, BIST100)
     */
    public function getMarketData(): array
    {
        return Cache::remember('market_data', 300, function () {
            $data = [
                'BIST 100' => ['value' => '9.854', 'change' => '+1.2%'],
                'DOLAR'    => ['value' => '32.50', 'change' => '+0.1%'],
                'EURO'     => ['value' => '35.10', 'change' => '+0.2%'],
                'ALTIN'    => ['value' => '2.450', 'change' => '-0.5%'],
            ];

            try {
                // Güvenilir, anlık çalışan API (truncgil)
                $response = Http::withoutVerifying()->timeout(10)->get('https://finans.truncgil.com/today.json');
                
                if ($response->successful()) {
                    $json = $response->json();
                    
                    if (isset($json['USD'])) {
                        $data['DOLAR']['value'] = $json['USD']['Satış'];
                        $data['DOLAR']['change'] = $json['USD']['Değişim'];
                    }
                    if (isset($json['EUR'])) {
                        $data['EURO']['value'] = $json['EUR']['Satış'];
                        $data['EURO']['change'] = $json['EUR']['Değişim'];
                    }
                    if (isset($json['gram-altin'])) {
                        $data['ALTIN']['value'] = $json['gram-altin']['Satış'];
                        $data['ALTIN']['change'] = $json['gram-altin']['Değişim'];
                    }
                }
            } catch (\Exception $e) {
                Log::error("Market data fetch error: " . $e->getMessage());
            }

            // BIST 100 için hala simülasyon (Ücretsiz public API nadir bulunur, varsa eklenebilir)
            $data['BIST 100']['change'] = $this->randomFluctuation();

            return $data;
        });
    }

    private function randomFluctuation(): string
    {
        $sign = rand(0, 1) ? '+' : '-';
        $val = rand(1, 15) / 10;
        return $sign . number_format($val, 1) . '%';
    }
}
