@extends('layouts.admin.app')

@section('title', 'Etiketler')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Etiketler</h1>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus fa-sm me-2"></i>Yeni Etiket Ekle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow-sm max-w-4xl">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Etiket Adı</th>
                            <th>Slug</th>
                            <th>Renk</th>
                            <th>Kullanım Sayısı</th>
                            <th style="width: 150px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td>
                                    <span class="badge text-white" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
                                </td>
                                <td><code>{{ $tag->slug }}</code></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle border mr-2" style="width: 20px; height: 20px; background-color: {{ $tag->color }}"></div>
                                        <small>{{ $tag->color }}</small>
                                    </div>
                                </td>
                                <td><span class="badge badge-secondary py-1.5 px-2">{{ $tag->usage_count }} haber</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.tags.edit', $tag) }}" 
                                           class="btn btn-xs btn-outline-primary mr-1"
                                           title="Düzenle">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </a>

                                        <form action="{{ route('admin.tags.destroy', $tag) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bu etiketi silmek istediğinizden emin misiniz?');">
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
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-tags fa-2x mb-3 d-block"></i>
                                    Kayıtlı etiket bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
