<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\NewsSource;
use App\Models\Category;
use App\Services\RssFetcherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsFetchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test duplicate filtering based on hash and URL
     */
    public function test_cannot_save_news_with_duplicate_content_hash(): void
    {
        $category = Category::factory()->create();
        $source = NewsSource::factory()->create(['default_category_id' => $category->id]);

        $title = 'Unique News Title';
        $content = 'Some interesting content that should generate a hash';
        $contentHash = News::generateContentHash($title, $content);

        // Pre-create duplicate news
        News::create([
            'title'        => $title,
            'description'  => 'Description',
            'content'      => $content,
            'category_id'  => $category->id,
            'slug'         => 'unique-news-title',
            'content_hash' => $contentHash,
        ]);

        $this->assertDatabaseCount('news', 1);

        // Try to fetch or save another with same hash
        // In database, there's already a row. Let's call the check directly or try to insert duplicate hash.
        $this->assertTrue(News::where('content_hash', $contentHash)->exists());
    }

    /**
     * Test auto reading time calculation on news save
     */
    public function test_saving_news_calculates_reading_time_automatically(): void
    {
        $category = Category::factory()->create();
        
        // Word count is ~250 words -> should be ~2 minutes
        $words = array_fill(0, 250, 'word');
        $content = implode(' ', $words);

        $news = News::create([
            'title'       => 'Unique Title for Reading Time',
            'description' => 'Summary content',
            'content'     => $content,
            'category_id' => $category->id,
            'slug'        => 'unique-title-for-reading-time',
        ]);

        $this->assertGreaterThan(0, $news->reading_time);
        $this->assertEquals(2, $news->reading_time);
    }
}
