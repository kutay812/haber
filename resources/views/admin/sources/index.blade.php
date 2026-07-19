@extends('layouts.admin.app')

@section('title', 'Haber Kaynakları')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Haber Kaynakları</h1>
        <a href="{{ route('admin.sources.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus fa-sm me-2"></i>Yeni Kaynak Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kaynak Adı</th>
                            <th>RSS URL</th>
                            <th>Tip</th>
                            <th>Güven Puanı</th>
                            <th>Varsayılan Kategori</th>
                            <th>Son Çekim</th>
                            <th>Durum</th>
                            <th style="width: 240px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sources as $source)
                            <tr>
                                <td>
                                    <strong>{{ $source->name }}</strong>
                                    @if($source->website_url)
                                        <a href="{{ $source->website_url }}" target="_blank" class="text-xs ml-1"><i class="fas fa-external-link-alt"></i></a>
                                    @endif
                                </td>
                                <td><code class="small text-muted">{{ Str::limit($source->url, 40) }}</code></td>
                                <td><span class="badge bg-secondary text-white">{{ strtoupper($source->type) }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress progress-sm mr-2 flex-grow-1" style="width: 60px;">
                                            <div class="progress-bar {{ $source->reliability_score >= 80 ? 'bg-success' : 'bg-warning' }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $source->reliability_score }}%"></div>
                                        </div>
                                        <span class="small font-weight-bold">%{{ $source->reliability_score }}</span>
                                    </div>
                                </td>
                                <td>{{ $source->defaultCategory->name ?? 'Yok' }}</td>
                                <td>{{ $source->last_fetched_at ? $source->last_fetched_at->format('d.m.Y H:i') : 'Hiç çekilmedi' }}</td>
                                <td>
                                    @if($source->is_active)
                                        <span class="badge bg-success text-white">Aktif</span>
                                    @else
                                        <span class="badge bg-danger text-white">Pasif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Fetch now form -->
                                        <form action="{{ route('admin.sources.fetch', $source) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-outline-success mr-1" title="Şimdi Haber Çek">
                                                <i class="fas fa-sync fa-sm"></i> Çek
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.sources.edit', $source) }}" 
                                           class="btn btn-xs btn-outline-primary mr-1"
                                           title="Düzenle">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </a>

                                        <form action="{{ route('admin.sources.destroy', $source) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bu kaynağı silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-xs btn-outline-danger" 
                                                    title="Sil">
                                                <i class="fas fa-trash fa-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-rss fa-2x mb-3 d-block"></i>
                                    Kayıtlı haber kaynağı bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $sources->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
