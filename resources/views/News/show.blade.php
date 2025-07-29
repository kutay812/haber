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
    .edit-form-container {
        background: #1e2735;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 4px 20px #00000022;
        margin-top: 2rem;
    }
    .edit-form-container label {
        color: #cbd5e1;
        margin-bottom: .3rem;
    }
    .edit-form-container input,
    .edit-form-container textarea {
        background: #181f2a;
        border: 1px solid #3576f6;
        color: #fff;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <a href="{{ route('home') }}" class="night-back mb-3">
                <i class="fa fa-arrow-left"></i> Ana Sayfaya Dön
            </a>

            <div class="card night-card mb-4">
                {{-- Haber Resmi --}}
                @if($haber->image && $haber->image->path)
                    <img src="{{ asset('storage/' . $haber->image->path) }}?v={{ $haber->image->updated_at->timestamp ?? time() }}"
                         class="card-img-top" alt="{{ $haber->title }}">
                @endif
                <div class="card-body pb-4 pt-4">
                    <h1 class="night-title">{{ $haber->title }}</h1>
                    <div class="night-meta mb-3">
                        <span class="night-badge"><i class="fa fa-tag"></i> {{ $haber->category->name ?? '-' }}</span>
                        <span class="ms-2"><i class="fa fa-user"></i> {{ $haber->user->name ?? '-' }}</span>
                        <span class="ms-3"><i class="fa fa-calendar"></i> {{ $haber->created_at->format('d.m.Y H:i') }}</span>
                        <span class="ms-3"><i class="fa fa-eye"></i> {{ $haber->views }}</span>
                    </div>
                    <div class="night-content">
                        {!! nl2br(e($haber->content)) !!}
                    </div>
                </div>
            </div>

            {{-- Haber Düzenleme Formu (Yetkililer için) --}}
            @if(auth()->check() && auth()->user()->hasAnyRole(['Admin', 'Editor', 'Super Admin']))
                <div class="edit-form-container">
                    <h4 class="mb-3">Haberi Düzenle</h4>
                    <form action="{{ route('news.update', $haber->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title">Başlık</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $haber->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description">Açıklama</label>
                            <input type="text" name="description" class="form-control" value="{{ old('description', $haber->description) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="content">İçerik</label>
                            <textarea name="content" class="form-control" rows="6" required>{{ old('content', $haber->content) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" class="form-control" required>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" @if($haber->category_id == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image">Yeni Görsel (isteğe bağlı)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-warning">Güncelle</button>
                    </form>
                </div>
            @endif

            {{-- Yorumlar --}}
            <div class="edit-form-container mt-5">
                <h4 class="mb-3">Yorumlar</h4>

                @foreach($haber->comments as $comment)
                    <div class="mb-3 p-3 rounded" style="background:#2e3a4d;">
                        <strong>{{ $comment->user->name }}</strong>
                        <small class="text-muted ms-2">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                        <div class="mt-1" id="comment-text-{{ $comment->id }}">{{ $comment->content }}</div>

                        @auth
                            @php
                                $user = auth()->user();
                                $canModify = $user->hasAnyRole(['Admin', 'Editor', 'Super Admin']) || $user->id === $comment->user_id;
                            @endphp

                            @if($canModify)
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-light" onclick="toggleEdit({{ $comment->id }})">Düzenle</button>

                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yorumu silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                    </form>
                                </div>

                                <form action="{{ route('comments.update', $comment->id) }}" method="POST" id="edit-form-{{ $comment->id }}" style="display:none;" class="mt-3">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="content" class="form-control mb-2" rows="3">{{ $comment->content }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Kaydet</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEdit({{ $comment->id }})">İptal</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @endforeach

                @auth
                <form action="{{ route('comments.store', $haber->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="content">Yorumunuz</label>
                        <textarea name="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Yorum Yap</button>
                </form>
                @else
                    <div class="alert alert-warning mt-3">
                        Yorum yapabilmek için <a href="{{ route('login.form') }}" class="text-info">giriş yapın</a>.
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit(id) {
    const form = document.getElementById('edit-form-' + id);
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
}
</script>
@endsection
