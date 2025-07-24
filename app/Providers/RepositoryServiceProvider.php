<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\NewsRepositoryInterface;
use App\Repositories\NewsRepository;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;
use App\Repositories\MainCategoryRepositoryInterface;
use App\Repositories\MainCategoryRepository;
use App\Repositories\NewsControllerRepositoryInterface;
use App\Repositories\NewsControllerRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Category
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        // News
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);

        // Image
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);

        // Main Category
        $this->app->bind(MainCategoryRepositoryInterface::class, MainCategoryRepository::class);

        // News Controller Repository
        $this->app->bind(NewsControllerRepositoryInterface::class, NewsControllerRepository::class);

        // Başka repository'ler ekleyeceksen aynı şekilde aşağıya yazabilirsin
        // $this->app->bind(OtherRepositoryInterface::class, OtherRepository::class);
    }

    public function boot()
    {
        //
    }
}