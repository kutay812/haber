@extends('admin.layouts.app')

@section('title', $news->title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $news->title }}</h1>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Açıklama</h5>
                        <p>{{ $news->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">İçerik</h5>
                        <div>{!! $news->content !!}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="font-weight-bold mb-3">Detaylar</h5>
                            
                            <div class="mb-3">
                                <strong>Kategori:</strong>
                                <span class="badge bg-primary">{{ $news->category->name }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Yazar:</strong>
                                <span>{{ $news->user->name }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Durum:</strong>
                                @if($news->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Pasif</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Görüntülenme:</strong>
                                <span>{{ $news->views }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Oluşturulma:</strong>
                                <span>{{ $news->created_at->format('d.m.Y H:i') }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Güncelleme:</strong>
                                <span>{{ $news->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" onsubmit="return confirm('Bu haberi silmek istediğinizden emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 