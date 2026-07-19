<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Standard XML Sitemap
     */
    public function index(): Response
    {
        $news = News::published()->latest()->limit(500)->get();
        $categories = Category::all();

        $content = view('seo.sitemap', compact('news', 'categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Google News XML Sitemap
     */
    public function news(): Response
    {
        // Google News sitemap only lists articles published in the last 2 days (48 hours)
        $news = News::published()
            ->with('newsSource')
            ->where('created_at', '>=', now()->subHours(48))
            ->latest()
            ->limit(1000)
            ->get();

        $content = view('seo.sitemap-news', compact('news'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * RSS Feed for aggregators
     */
    public function feed(): Response
    {
        $news = News::with(['category', 'user'])->published()->latest()->limit(50)->get();

        $content = view('seo.rss', compact('news'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
