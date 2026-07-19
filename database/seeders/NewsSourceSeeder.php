<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class NewsSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            [
                'name' => 'TRT Haber',
                'slug' => 'trt-haber',
                'url'  => 'https://www.trthaber.com/sondakika.rss',
                'type' => 'rss',
                'website_url' => 'https://www.trthaber.com',
                'reliability_score' => 90,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 15,
            ],
            [
                'name' => 'NTV',
                'slug' => 'ntv',
                'url'  => 'https://www.ntv.com.tr/son-dakika.rss',
                'type' => 'rss',
                'website_url' => 'https://www.ntv.com.tr',
                'reliability_score' => 85,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 20,
            ],
            [
                'name' => 'Sözcü',
                'slug' => 'sozcu',
                'url'  => 'https://www.sozcu.com.tr/rss/tum-haberler.xml',
                'type' => 'rss',
                'website_url' => 'https://www.sozcu.com.tr',
                'reliability_score' => 75,
                'is_active' => true,
                'auto_publish' => false,
                'fetch_interval_minutes' => 30,
            ],
            [
                'name' => 'Hürriyet',
                'slug' => 'hurriyet',
                'url'  => 'https://www.hurriyet.com.tr/rss/gundem',
                'type' => 'rss',
                'website_url' => 'https://www.hurriyet.com.tr',
                'reliability_score' => 80,
                'is_active' => true,
                'auto_publish' => false,
                'fetch_interval_minutes' => 30,
            ],
            [
                'name' => 'Cumhuriyet',
                'slug' => 'cumhuriyet',
                'url'  => 'https://www.cumhuriyet.com.tr/rss',
                'type' => 'rss',
                'website_url' => 'https://www.cumhuriyet.com.tr',
                'reliability_score' => 78,
                'is_active' => true,
                'auto_publish' => false,
                'fetch_interval_minutes' => 30,
            ],
            [
                'name' => 'Anadolu Ajansı',
                'slug' => 'anadolu-ajansi',
                'url'  => 'https://www.aa.com.tr/tr/rss/default?cat=guncel',
                'type' => 'rss',
                'website_url' => 'https://www.aa.com.tr',
                'reliability_score' => 95,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 15,
            ],
            [
                'name' => 'Bloomberg HT',
                'slug' => 'bloomberg-ht',
                'url'  => 'https://www.bloomberght.com/rss',
                'type' => 'rss',
                'website_url' => 'https://www.bloomberght.com',
                'reliability_score' => 90,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 15,
            ],
            [
                'name' => 'Habertürk',
                'slug' => 'haberturk',
                'url'  => 'https://www.haberturk.com/rss',
                'type' => 'rss',
                'website_url' => 'https://www.haberturk.com',
                'reliability_score' => 82,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 20,
            ],
            [
                'name' => 'DW Türkçe',
                'slug' => 'dw-turkce',
                'url'  => 'http://rss.dw.com/rdf/rss-tur-all',
                'type' => 'rss',
                'website_url' => 'https://www.dw.com/tr',
                'reliability_score' => 92,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 20,
            ],
            [
                'name' => 'BBC Türkçe',
                'slug' => 'bbc-turkce',
                'url'  => 'https://feeds.bbci.co.uk/turkce/rss.xml',
                'type' => 'rss',
                'website_url' => 'https://www.bbc.com/turkce',
                'reliability_score' => 95,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 20,
            ],
            [
                'name' => 'Milliyet',
                'slug' => 'milliyet',
                'url'  => 'https://www.milliyet.com.tr/rss/rssNew/SonDakikaRss.xml',
                'type' => 'rss',
                'website_url' => 'https://www.milliyet.com.tr',
                'reliability_score' => 78,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 30,
            ],
            [
                'name' => 'Sabah',
                'slug' => 'sabah',
                'url'  => 'https://www.sabah.com.tr/rss/anasayfa.xml',
                'type' => 'rss',
                'website_url' => 'https://www.sabah.com.tr',
                'reliability_score' => 76,
                'is_active' => true,
                'auto_publish' => true,
                'fetch_interval_minutes' => 30,
            ],
        ];

        foreach ($sources as $source) {
            NewsSource::updateOrCreate(
                ['slug' => $source['slug']],
                $source
            );
        }
    }
}
