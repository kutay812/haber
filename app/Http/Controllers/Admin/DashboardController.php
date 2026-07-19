<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\User;
use App\Models\NewsSource;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard statistics
     */
    public function index()
    {
        $stats = [
            'news_count'     => News::count(),
            'category_count' => Category::count(),
            'user_count'     => User::count(),
            'source_count'   => NewsSource::count(),
            'tag_count'      => Tag::count(),
            'comment_count'  => Comment::count(),
            'total_views'    => News::sum('views'),
        ];

        $latestNews = News::with(['category', 'newsSource'])->latest()->limit(5)->get();
        $sources = NewsSource::orderByDesc('last_fetched_at')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'latestNews', 'sources'));
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        return redirect()->back()->with('success', 'Önbellek temizleme işlemi başarıyla tamamlandı.');
    }

    /**
     * Tüm kaynaklardan haber çekmeyi manuel tetikle
     */
    public function fetchNews()
    {
        try {
            Artisan::call('news:fetch', ['--force' => true]);
            $output = Artisan::output();
            
            \Illuminate\Support\Facades\Log::info("Admin tarafından manuel tüm haber çekme tetiklendi:\n" . $output);

            return redirect()->back()->with('success', 'Bot tüm kaynaklardan başarıyla haberleri çekti. (Çıktı loglara yazıldı.)');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Manuel haber çekme hatası: " . $e->getMessage());
            return redirect()->back()->with('error', 'Haber botu çalıştırılırken bir hata oluştu: ' . $e->getMessage());
        }
    }
}
