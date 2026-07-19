@extends('layouts.admin.app')

@section('title', 'Haber Kaynağını Düzenle')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Haber Kaynağını Düzenle: {{ $source->name }}</h1>
    </div>

    <div class="card shadow-sm max-w-2xl">
        <div class="card-body">
            <form action="{{ route('admin.sources.update', $source) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="name" class="font-weight-bold">Kaynak Adı *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $source->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="url" class="font-weight-bold">RSS Feed URL *</label>
                    <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $source->url) }}" required>
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="type" class="font-weight-bold">Kaynak Türü *</label>
                            <select name="type" id="type" class="form-control">
                                <option value="rss" @selected($source->type === 'rss')>RSS Feed</option>
                                <option value="api" @selected($source->type === 'api')>API Endpoint</option>
                                <option value="scraper" @selected($source->type === 'scraper')>Custom Web Scraper</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="reliability_score" class="font-weight-bold">Güvenilirlik Skoru (0-100) *</label>
                            <input type="number" name="reliability_score" id="reliability_score" class="form-control" value="{{ old('reliability_score', $source->reliability_score) }}" min="0" max="100" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="default_category_id" class="font-weight-bold">Varsayılan Kategori</label>
                            <select name="default_category_id" id="default_category_id" class="form-control">
                                <option value="">Seçiniz</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($source->default_category_id === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="fetch_interval_minutes" class="font-weight-bold">Çekim Sıklığı (Dakika) *</label>
                            <input type="number" name="fetch_interval_minutes" id="fetch_interval_minutes" class="form-control" value="{{ old('fetch_interval_minutes', $source->fetch_interval_minutes) }}" min="5" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="website_url" class="font-weight-bold">Kaynak Web Sitesi URL</label>
                    <input type="url" name="website_url" id="website_url" class="form-control" value="{{ old('website_url', $source->website_url) }}">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="hidden" name="is_active" value="0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked($source->is_active)>
                            <label class="custom-control-label font-weight-bold" for="is_active">Aktif (Çekim yapılır)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="auto_publish" value="0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="auto_publish" name="auto_publish" value="1" @checked($source->auto_publish)>
                            <label class="custom-control-label font-weight-bold" for="auto_publish">Otomatik Yayınla</label>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="notes" class="font-weight-bold">Notlar</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $source->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.sources.index') }}" class="btn btn-secondary mr-2">İptal</a>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
