@extends('layouts.app')

@section('content')
<style>
    body {
        background: #181f2a !important;
        color: #f1f5fa !important;
    }
    .sidebar-custom {
        width: 260px;
        min-height: 100vh;
        background: #232d3b;
        color: #fff;
        padding: 30px 20px;
        position: fixed;
        top: 0; left: 0; bottom: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 100;
    }
    .sidebar-custom .profile-img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 16px;
        border: 3px solid #3498db;
        background: #fff;
    }
    .sidebar-custom h4 { margin-bottom: 5px; color: #fff; }
    .sidebar-custom .role { font-size: 0.95em; color: #bdc3c7; margin-bottom: 10px; }
    .sidebar-custom .sitename { font-weight: bold; margin: 16px 0 10px 0; font-size: 1.2em; color: #fff; }
    .sidebar-custom a, .sidebar-custom button {
        display: block;
        width: 100%;
        padding: 8px 0;
        margin: 8px 0;
        text-align: center;
        border-radius: 6px;
        border: none;
        background: #3498db;
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s;
    }
    .sidebar-custom a:hover, .sidebar-custom button:hover {
        background: #217dbb;
    }
    .sidebar-custom .small-link {
        color: #8ec4fa;
        background: none;
        border: none;
        font-size: 0.95em;
        padding: 0;
        margin: 0;
        display: inline;
        width: auto;
        text-align: left;
        box-shadow: none;
    }
    .sidebar-custom .small-link:hover { text-decoration: underline; background: none; color: #e0e0e0; }
    .sidebar-custom form { width: 100%; }
    @media (max-width: 991px) {
        .sidebar-custom { display: none !important; }
        .main-content-custom { margin-left: 0 !important; }
    }
    .main-content-custom { margin-left: 270px; }
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
    .text-muted {
    color: #d1cfcbff !important;   /* Karakter limiti ve sayaç rengi */
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
    /* Haber Ekleme Modal Stilleri */
    .modal-content {
        background: #232d3b;
        border: none;
        border-radius: 1.2rem;
        color: #f1f5fa;
    }
    .modal-header {
        border-bottom: 1px solid #3e4a5b;
    }
    .modal-title {
        color: #fff;
    }
    .btn-close {
        filter: invert(1);
    }
    .form-control, .form-select {
        background: #181f2a;
        border: 1.5px solid #3576f6;
        color: #f1f5fa;
        border-radius: 0.5rem;
    }
    .form-control:focus, .form-select:focus {
        background: #181f2a;
        border-color: #57a7ff;
        color: #f1f5fa;
        box-shadow: 0 0 0 0.2rem rgba(53, 118, 246, 0.25);
    }
    .form-control::placeholder {
        color: #94a3b8;
    }
    .form-label {
        color: #f8f8f8ff;
        font-weight: 500;
    }
    .btn-success {
        background: linear-gradient(90deg, #10b981, #059669);
        border: none;
        border-radius: 2rem;
    }
    .btn-success:hover {
        background: linear-gradient(90deg, #059669, #047857);
    }
    .btn-secondary {
        background: #374151;
        border: none;
        border-radius: 2rem;
    }
    .btn-secondary:hover {
        background: #4b5563;
    }
    .add-news-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        font-size: 24px;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
        z-index: 1000;
    }
    .add-news-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 32px rgba(16, 185, 129, 0.6);
        background: linear-gradient(135deg, #059669, #047857);
    }
    .add-news-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
    }
    .image-preview {
        max-width: 200px;
        max-height: 150px;
        border-radius: 0.5rem;
        margin-top: 10px;
        border: 2px solid #3576f6;
    }
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal.show {
        display: flex !important;
        align-items: center;
        justify-content: center;
        opacity: 1;
    }
    .modal-dialog {
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }
    .modal.show .modal-dialog {
        transform: scale(1);
    }
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1040;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-backdrop.show {
        opacity: 1;
    }
    .loading {
        opacity: 0.7;
        pointer-events: none;
    }
    .btn.loading {
        position: relative;
    }
    .btn.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin: -8px 0 0 -8px;
        border: 2px solid transparent;
        border-top: 2px solid #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .invalid-feedback {
        display: block;
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #ef4444;
    }
    .character-counter {
        display: block;
        margin-top: 0.25rem;
    }
</style>

<div class="sidebar-custom">
    <div class="sitename mb-3" style="font-size:1.3em;">Gece Haber Portalı</div>
    @auth
        <img src="{{ auth()->user()->profile_image
            ? asset('storage/' . auth()->user()->profile_image)
            : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
            class="profile-img" alt="Profil Foto">
        <h4>{{ auth()->user()->name }}</h4>
        <div class="role">
            @php
                $roles = auth()->user()->roles->pluck('name')->toArray();
            @endphp
            {{ $roles && count($roles) ? implode(' / ', $roles) : 'Kullanıcı' }}
        </div>
        <a href="{{ route('profile') }}">Profilimi Düzenle</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Çıkış Yap</button>
        </form>
    @else
        <div class="w-100">
            @if(session('success'))
                <div class="alert alert-success py-2 my-2">{{ session('success') }}</div>
            @endif
            @if(session('register_success'))
                <div class="alert alert-success py-2 my-2">{{ session('register_success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2 my-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="mb-3" id="loginForm" @if(request()->has('register')) style="display:none" @endif>
                @csrf
                <div class="mb-2">
                    <input type="email" name="email" class="form-control" placeholder="E-Posta" required value="{{ old('email') }}">
                </div>
                <div class="mb-2">
                    <input type="password" name="password" class="form-control" placeholder="Şifre" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                <button type="button" class="small-link mt-2" onclick="showRegister()">Kayıt Ol</button>
            </form>

            <form method="POST" action="{{ route('register.submit') }}" class="mb-3" id="registerForm" style="display:none">
                @csrf
                <div class="mb-2">
                    <input type="text" name="name" class="form-control" placeholder="Ad Soyad" required>
                </div>
                <div class="mb-2">
                    <input type="email" name="email" class="form-control" placeholder="E-Posta" required>
                </div>
                <div class="mb-2">
                    <input type="password" name="password" class="form-control" placeholder="Şifre" required>
                </div>
                <div class="mb-2">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Şifre Tekrar" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Kayıt Ol</button>
                <button type="button" class="small-link mt-2" onclick="showLogin()">Giriş Yap</button>
            </form>
        </div>
        <script>
            function showRegister() {
                document.getElementById('loginForm').style.display = 'none';
                document.getElementById('registerForm').style.display = 'block';
            }
            function showLogin() {
                document.getElementById('loginForm').style.display = 'block';
                document.getElementById('registerForm').style.display = 'none';
            }
        </script>
    @endauth
</div>

<div class="main-content-custom">
    <div class="container py-4">
        <!-- Header -->
        <div class="navy-header d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div>
                <h1 class="navy-title mb-1">
                    <i class="fa fa-moon"></i> Gece Haber Portalı
                </h1>
                <span class="news-meta">En güncel ve en doğru haberler</span>
            </div>
            <div class="d-none d-md-block"></div>
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
</div>

<!-- Haber Ekleme Butonu (Sadece giriş yapmış kullanıcılar için) -->
@auth
<button type="button" class="add-news-btn" onclick="openNewsModal()" title="Yeni Haber Ekle">
    <i class="fa fa-plus"></i>
</button>
@endauth

<!-- Haber Ekleme Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1" role="dialog" aria-labelledby="addNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewsModalLabel">
                    <i class="fa fa-plus-circle me-2"></i>Yeni Haber Ekle
                </h5>
                <button type="button" class="btn-close" onclick="closeNewsModal()" aria-label="Close"></button>
            </div>
            <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" id="addNewsForm">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fa fa-heading me-1"></i>Haber Başlığı *
                                </label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="Örn: Son Dakika: Önemli Gelişme..." 
                                       required maxlength="200">
                                <small class="text-muted">En az 5, en fazla 200 karakter</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fa fa-align-left me-1"></i>Kısa Açıklama *
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          placeholder="Haberin özetini buraya yazın. Bu metin haber kartlarında görünecek..." 
                                          required maxlength="300"></textarea>
                                <small class="text-muted">En az 20, en fazla 300 karakter</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">
                                    <i class="fa fa-file-text me-1"></i>Haber İçeriği *
                                </label>
                                <textarea class="form-control" id="content" name="content" rows="10" 
                                          placeholder="Haberin detaylı içeriğini buraya yazın..." 
                                          required minlength="50"></textarea>
                                <small class="text-muted">En az 50 karakter</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    <i class="fa fa-tags me-1"></i>Kategori *
                                </label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Kategori Seçin</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="fa fa-image me-1"></i>Haber Görseli
                                </label>
                                <input type="file" class="form-control" id="image" name="image" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif" 
                                       onchange="previewImage(this)">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle me-1"></i>
                                    JPG, PNG, GIF - Max 2MB
                                </small>
                                <div id="imagePreview" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeNewsModal()">
                        <i class="fa fa-times me-1"></i>İptal
                    </button>
                    <button type="submit" class="btn btn-success" id="publishBtn">
                        <i class="fa fa-paper-plane me-1"></i>Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Backdrop -->
<div class="modal-backdrop fade" id="modalBackdrop" style="display: none;"></div>

<script>
    // Modal fonksiyonları
    function openNewsModal() {
        resetForm();
        document.getElementById('modalBackdrop').style.display = 'block';
        document.getElementById('addNewsModal').style.display = 'flex';
        setTimeout(() => {
            document.getElementById('modalBackdrop').classList.add('show');
            document.getElementById('addNewsModal').classList.add('show');
        }, 10);
        document.body.style.overflow = 'hidden';
        document.getElementById('title').focus();
    }

    function closeNewsModal() {
        document.getElementById('addNewsModal').classList.remove('show');
        document.getElementById('modalBackdrop').classList.remove('show');
        setTimeout(() => {
            document.getElementById('addNewsModal').style.display = 'none';
            document.getElementById('modalBackdrop').style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }

    document.getElementById('modalBackdrop').addEventListener('click', closeNewsModal);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('addNewsModal').classList.contains('show')) {
            closeNewsModal();
        }
    });

    function resetForm() {
        document.getElementById('addNewsForm').reset();
        document.getElementById('imagePreview').innerHTML = '';
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.classList.remove('is-invalid');
        });
        updateCharacterCounter('title', 200);
        updateCharacterCounter('description', 300);
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 10 * 1024 * 1024) {
                alert('Dosya boyutu 10MB\'dan büyük olamaz!');
                input.value = '';
                return;
            }
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Sadece JPG, PNG ve GIF formatları desteklenir!');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.innerHTML = `
                    <img src="${e.target.result}" class="image-preview" alt="Görsel Önizleme">
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fa fa-check me-1"></i>
                            ${file.name} (${(file.size / 1024).toFixed(1)} KB)
                        </small>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeImage()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `;
                preview.appendChild(imgContainer);
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').innerHTML = '';
    }

    function validateForm() {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const content = document.getElementById('content').value.trim();
        const category = document.getElementById('category_id').value;

        let isValid = true;

        if (!title || title.length < 5) {
            showFieldError('title', 'Başlık en az 5 karakter olmalıdır!');
            isValid = false;
        } else if (title.length > 200) {
            showFieldError('title', 'Başlık en fazla 200 karakter olabilir!');
            isValid = false;
        } else {
            clearFieldError('title');
        }

        if (!description || description.length < 20) {
            showFieldError('description', 'Açıklama en az 20 karakter olmalıdır!');
            isValid = false;
        } else if (description.length > 300) {
            showFieldError('description', 'Açıklama en fazla 300 karakter olabilir!');
            isValid = false;
        } else {
            clearFieldError('description');
        }

        if (!content || content.length < 50) {
            showFieldError('content', 'İçerik en az 50 karakter olmalıdır!');
            isValid = false;
        } else {
            clearFieldError('content');
        }

        if (!category) {
            showFieldError('category_id', 'Lütfen bir kategori seçin!');
            isValid = false;
        } else {
            clearFieldError('category_id');
        }

        return isValid;
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        field.classList.add('is-invalid');
        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    function showLoading(message = 'Yükleniyor...') {
        const publishBtn = document.getElementById('publishBtn');
        publishBtn.classList.add('loading');
        publishBtn.innerHTML = `<i class="fa fa-spinner fa-spin me-1"></i>${message}`;
        publishBtn.disabled = true;
    }

    document.getElementById('addNewsForm').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        showLoading('Haber yayınlanıyor...');
    });

    function setupCharacterCounter(inputId, maxLength) {
        const input = document.getElementById(inputId);
        let counter = input.parentNode.querySelector('.character-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.className = 'text-muted character-counter';
            input.parentNode.appendChild(counter);
        }
        function updateCounter() {
            counter.textContent = `${input.value.length}/${maxLength} karakter`;
            counter.className = (maxLength - input.value.length) < 20
                ? 'text-warning character-counter'
                : 'text-muted character-counter';
        }
        input.removeEventListener('input', input._counterListener || (()=>{}));
        input._counterListener = updateCounter;
        input.addEventListener('input', updateCounter);
        updateCounter();
    }

    function updateCharacterCounter(inputId, maxLength) {
        setupCharacterCounter(inputId, maxLength);
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupCharacterCounter('title', 200);
        setupCharacterCounter('description', 300);
    });
</script>
@endsection
