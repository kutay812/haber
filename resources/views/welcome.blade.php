@extends('layouts.app')

@section('content')
<style>
    body {
        background: #181f2a !important;
        color: #f1f5fa !important;
    }
    .navy-header {
        background: #232d3b;
        border-radius: 1.2rem;
        padding: 2rem 2rem 1rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 24px rgba(20,30,45,0.28);
    }
    .navy-title {
        color: #fff;
        letter-spacing: 1px;
    }
    .navy-menu {
        background: #232d3b;
        border-radius: 2rem;
        overflow-x: auto;
        padding: 0.5rem 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 16px rgba(30,40,70,0.10);
    }
    .nav-pills .nav-link {
        color: #94a3b8;
        margin: 0 0.2rem;
        background: none;
        border-radius: 2rem;
        transition: all 0.25s;
        font-weight: 500;
    }
    .nav-pills .nav-link.active, .nav-pills .nav-link:hover {
        background: linear-gradient(90deg, #2563eb, #3576f6);
        color: #fff;
        box-shadow: 0 2px 12px #2563eb33;
    }
    .search-box input {
        background: #181f2a;
        border: 1.5px solid #3576f6;
        color: #f1f5fa;
    }
    .search-box input::placeholder {
        color: #94a3b8;
    }
    .search-box button {
        background: linear-gradient(90deg, #2563eb, #3576f6);
        color: #fff;
        border: none;
    }
    .news-card {
        background: #232d3b;
        border: none;
        color: #f1f5fa;
        border-radius: 1.2rem;
        box-shadow: 0 4px 20px rgba(30,40,60,0.16);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .news-card:hover {
        transform: translateY(-4px) scale(1.025);
        box-shadow: 0 8px 32px #2563eb3a;
    }
    .news-card-title a {
        color: #f1f5fa;
        text-decoration: none;
        transition: color 0.2s;
    }
    .news-card-title a:hover {
        color: #57a7ff;
    }
    .news-meta {
        color: #94a3b8;
        font-size: 0.93rem;
    }
    .news-desc {
        color: #c9d6e8;
    }
    .btn-outline-primary, .btn-outline-primary:hover, .btn-outline-primary:focus {
        border-color: #3576f6;
        color: #3576f6;
        background: none;
        border-radius: 2rem;
    }
    .btn-outline-primary:hover, .btn-outline-primary:focus {
        background: linear-gradient(90deg, #2563eb, #3576f6);
        color: #fff;
    }
    .pagination .page-link {
        background: #232d3b;
        color: #94a3b8;
        border: none;
        margin: 0 3px;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(90deg, #2563eb, #3576f6);
        color: #fff;
        border-radius: 1.2rem;
    }
</style>
<div class="container py-4">

    <!-- Header -->
    <div class="navy-header d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div>
            <h1 class="navy-title mb-1">
                <i class="fa fa-moon"></i> Gece Haber Portalı
            </h1>
            <span class="news-meta">En güncel ve en doğru haberler, gece mavisi tasarımla.</span>
        </div>
        <div class="d-none d-md-block">
            <!-- Ekstra buton, profil, vs eklemek istersen buraya -->
        </div>
    </div>

    <!-- Kategori Menüsü ve Arama -->
    <div class="row align-items-center mb-4">
        <div class="col-lg-8 mb-3 mb-lg-0">
            <nav class="nav navy-menu nav-pills flex-nowrap">
                <a class="nav-link {{ !isset($category) ? 'active' : '' }}" href="{{ route('home') }}">Tümü</a>
                @foreach($categories as $cat)
                    <a class="nav-link {{ (isset($category) && $category->id === $cat->id) ? 'active' : '' }}"
                        href="{{ route('category.news', $cat->slug) }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </nav>
        </div>
        <div class="col-lg-4">
            <form action="{{ isset($category) ? route('category.news', $category->slug) : route('home') }}" method="GET" class="d-flex search-box">
                <input type="text" name="search" class="form-control me-2" placeholder="Başlıkta ara..." value="{{ request('search') }}">
                <button class="btn px-4" type="submit">
                    <i class="fa fa-search"></i> Ara
                </button>
            </form>
        </div>
    </div>

    <!-- Haberler -->
    <div class="row">
        @forelse($news as $haber)
            <div class="col-sm-12 col-md-6 col-lg-4 mb-4 d-flex">
                <div class="card news-card w-100 h-100">
                    @if($haber->image && $haber->image->path)
                        <img src="{{ asset('storage/' . $haber->image->path) }}" class="card-img-top" alt="{{ $haber->title }}" style="max-height:200px; object-fit:cover; border-top-left-radius:1.2rem; border-top-right-radius:1.2rem;">
                    @else
                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height:200px; border-top-left-radius:1.2rem; border-top-right-radius:1.2rem;">
                            <span class="text-secondary">[Görsel Yok]</span>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1 news-card-title">
                            <a href="{{ route('news.show', $haber->slug) }}">
                                {{ $haber->title }}
                            </a>
                        </h5>
                        <div class="news-meta mb-2">
                            {{ $haber->category->name ?? '-' }} | {{ $haber->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="news-desc mb-2" style="min-height:48px;">
                            {{ Str::limit($haber->description, 70) }}
                        </div>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="news-meta">{{ $haber->views }} okuma</span>
                            <a href="{{ route('news.show', $haber->slug) }}" class="btn btn-outline-primary btn-sm">Detay</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info" style="background:#232d3b; color:#94a3b8; border:none;">Haber bulunamadı.</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center my-4">
        {{ $news->withQueryString()->links() }}
    </div>
</div>
@endsection
