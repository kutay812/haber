<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\NewsRepositoryInterface::class,
            \App\Repositories\NewsRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        // Share categories and settings with all views (only if database connection works and table exists)
        if (!app()->runningInConsole()) {
            try {
                if (Schema::hasTable('categories')) {
                    $globalCategories = \App\Models\Category::orderBy('name')->get();
                    view()->share('globalCategories', $globalCategories);
                }
                if (Schema::hasTable('settings')) {
                    $siteName = \App\Models\Setting::get('site_name', 'HaberPortal');
                    $siteDesc = \App\Models\Setting::get('site_description', 'En güncel ve doğru haberler');
                    $breakingNewsEnabled = \App\Models\Setting::get('breaking_news_enabled', true);
                    
                    view()->share('siteName', $siteName);
                    view()->share('siteDesc', $siteDesc);
                    view()->share('breakingNewsEnabled', $breakingNewsEnabled);
                }
            } catch (\Exception $e) {
                // Ignore exceptions during seeding/migrations
            }
        }
    }
}