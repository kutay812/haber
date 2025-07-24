@extends('layouts.app')
@section('content')
<style>
    body {
        background: #181f2a !important;
        color: #f1f5fa !important;
    }
    .night-card {
        background: #232d3b;
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px rgba(20,30,45,0.33);
        border: none;
        margin-top: 32px;
        color: #f1f5fa;
    }
    .night-card .card-img-top {
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
        max-height: 380px;
        object-fit: cover;
        box-shadow: 0 2px 12px #2563eb20;
    }
    .night-title {
        font-size: 2.3rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: .5px;
        margin-bottom: 0.5rem;
    }
    .night-meta {
        color: #94a3b8;
        font-size: 0.98rem;
        margin-bottom: 1rem;
    }
    .night-content {
        color: #c9d6e8;
        font-size: 1.18rem;
        line-height: 1.7;
    }
    .night-badge {
        background: linear-gradient(90deg, #2563eb, #3576f6);
        color: #fff;
        font-weight: 500;
        border-radius: 2rem;
        padding: 0.2em 1em;
        font-size: 1rem;
        margin-right: 0.5em;
    }
    .night-back {
        display: inline-block;
        background: linear-gradient(90deg, #2563eb, #3576f6);
        color: #fff !important;
        font-weight: 500;
        border-radius: 2rem;
        padding: 0.55em 1.6em;
        font-size: 1rem;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px #2563eb20;
        border: none;
        text-decoration: none !important;
        transition: background 0.18s;
    }
    .night-back i {
        margin-right: 8px;
    }
    .night-back:hover {
        background: #1a2a50;
        color: #fff !important;
        text-decoration: none;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <!-- Ana sayfaya dön butonu -->
            <a href="{{ route('home') }}" class="night-back mb-3">
                <i class="fa fa-arrow-left"></i> Ana Sayfaya Dön
            </a>

            <div class="card night-card mb-4">
                @if($haber->image && $haber->image->path)
                    <img src="{{ asset('storage/' . $haber->image->path) }}" class="card-img-top" alt="{{ $haber->title }}">
                @endif
                <div class="card-body pb-4 pt-4">
                    <h1 class="night-title">{{ $haber->title }}</h1>
                    <div class="night-meta mb-3">
                        <span class="night-badge">
                            <i class="fa fa-tag"></i>
                            {{ $haber->category->name ?? '-' }}
                        </span>
                        <span class="ms-2">
                            <i class="fa fa-user"></i>
                            {{ $haber->user->name ?? '-' }}
                        </span>
                        <span class="ms-3">
                            <i class="fa fa-calendar"></i>
                            {{ $haber->created_at->format('d.m.Y H:i') }}
                        </span>
                        <span class="ms-3">
                            <i class="fa fa-eye"></i>
                            {{ $haber->views }}
                        </span>
                    </div>
                    <div class="night-content">
                        {!! nl2br(e($haber->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
