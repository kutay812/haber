<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;

// Ana sayfa ve haberler (public)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{slug}', [HomeController::class, 'index'])->name('category.news');
Route::get('/haber/{slug}', [HomeController::class, 'show'])->name('news.show');

// Admin Login ve Şifre işlemleri (public)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
    Route::get('/admin/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('admin.password.request');
    Route::post('/admin/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('/admin/reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('admin.password.reset');
    Route::post('/admin/reset-password', [PasswordResetController::class, 'resetPassword'])->name('admin.password.update');
});

// Admin paneli ve kaynak (resource) route’lar
// Sadece Super Admin, Admin, Editor rollerine açık!
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:Super Admin|Admin|Editor'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/news/search', [NewsController::class, 'search'])->name('news.search');
        Route::get('/categories/search', [AdminCategoryController::class, 'search'])->name('categories.search');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search')->middleware('permission:users.view');
        Route::resource('news', NewsController::class);
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('users', UserController::class)->middleware('permission:users.view');
        Route::delete('/profile/image', [ProfileController::class, 'deleteProfileImage'])->name('profile.image.delete');
        Route::get('/profile/image/{filename}', [ProfileController::class, 'showProfileImage'])->name('profile.image.show');
    });
