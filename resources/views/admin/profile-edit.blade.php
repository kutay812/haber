@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1 class="header-title mb-4"><i class="fas fa-user-cog"></i> Profil Yönetimi</h1>
    <div class="row">
        <!-- Sol Taraf - Profil Düzenleme Formu -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-edit"></i> Profil Düzenle</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Profil Resmi -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <div class="profile-image-container" id="profile-image-area">
                                    <img id="profile-image-preview" 
                                         src="{{ auth()->user()->profile_image ? auth()->user()->profile_image_url : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                         alt="Profil Resmi">
                                    <div class="profile-image-overlay" tabindex="0">
                                        <i class="fas fa-camera"></i> Resmi Değiştir
                                    </div>
                                </div>
                                <input type="file" name="profile-image" id="profile-image-input" accept="image/*" style="display: none;">
                                <p class="text-muted small">JPEG, PNG veya GIF (Max. 10MB)</p>
                                @error('profile-image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user"></i> Ad Soyad <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> E-posta <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user-tag"></i> Rolleriniz
                                    </label><br>
                                    @foreach(auth()->user()->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="new_password" class="form-label">
                                        <i class="fas fa-lock"></i> Yeni Şifre
                                    </label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                           id="new_password" name="new_password" placeholder="Yeni şifrenizi girin">
                                    <small class="form-text text-muted">Şifrenizi değiştirmek istemiyorsanız boş bırakın.</small>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="current_password" class="form-label">
                                        <i class="fas fa-key"></i> Mevcut Şifre <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" placeholder="Mevcut şifrenizi girin" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Güvenlik için mevcut şifrenizi girmeniz gerekiyor.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Profili Güncelle
                            </button>
                            <a href="{{ route('admin.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left"></i> Admin Paneline Geri Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf - Kullanıcı Bilgileri ve Güvenlik Notları -->
        <div class="col-lg-4">
            <!-- Kullanıcı Bilgileri Kartı -->
            <div class="card user-info-card mb-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Kullanıcı Bilgileri</h3>
                </div>
                <div class="card-body">
                    <div class="profile-image-container mb-3">
                        <img src="{{ auth()->user()->profile_image ? auth()->user()->profile_image_url : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                             alt="Profil Resmi">
                    </div>
                    <div class="user-info text-center">
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="user-details">
                        <p><strong>Kullanıcı ID:</strong> <span>{{ auth()->user()->id }}</span></p>
                        <p><strong>Kayıt Tarihi:</strong> <span>{{ auth()->user()->created_at->format('d.m.Y H:i') }}</span></p>
                        <p><strong>Son Güncelleme:</strong> <span>{{ auth()->user()->updated_at->format('d.m.Y H:i') }}</span></p>
                        <p>
                            <strong>Roller:</strong>
                            <span>
                                @foreach(auth()->user()->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Güvenlik Notları Kartı -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-shield-alt"></i> Güvenlik Notları</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled security-notes">
                        <li><i class="fas fa-check text-success"></i> Güçlü şifre kullanın (büyük/küçük harf, sayı, özel karakter)</li>
                        <li><i class="fas fa-check text-success"></i> Şifrenizi düzenli olarak değiştirin</li>
                        <li><i class="fas fa-check text-success"></i> Şifrenizi kimseyle paylaşmayın</li>
                        <li><i class="fas fa-check text-success"></i> Her oturum sonunda çıkış yapın</li>
                        <li><i class="fas fa-check text-success"></i> Profil resminizi güncel tutun</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #4e73df;
    --secondary-color: #858796;
    --success-color: #1cc88a;
    --danger-color: #e74a3b;
}

.header-title {
    color: var(--primary-color);
    text-align: center;
    margin-bottom: 30px;
    font-weight: 700;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: 1px solid #e3e6f0;
    margin-bottom: 1.5rem;
}

.card-header {
    background: linear-gradient(135deg, var(--primary-color), #2e59d9);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    padding: 1rem;
}

.card-header .card-title {
    margin: 0;
    font-size: 1.1rem;
}

.profile-image-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 20px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e3e6f0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
}

.profile-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.2s;
}

.profile-image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 8px;
    text-align: center;
    font-size: 13px;
    opacity: 0;
    transition: opacity 0.3s;
    cursor: pointer;
}

.profile-image-container:hover .profile-image-overlay,
.profile-image-overlay:focus {
    opacity: 1;
}

.user-info-card .card-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 25px;
}

.badge {
    font-weight: 500;
    padding: 0.6em 0.9em;
    margin: 0 3px 5px 0;
    border-radius: 20px;
    font-size: 0.9em;
}

.alert {
    border-radius: 0.5rem;
}

.security-notes li {
    margin-bottom: 12px;
    padding-left: 5px;
}

.security-notes li i {
    margin-right: 10px;
    font-size: 18px;
}

.user-details {
    width: 100%;
    border-top: 1px solid #eee;
    padding-top: 20px;
    margin-top: 20px;
}

.user-details p {
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
}

.user-details p strong {
    color: var(--primary-color);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageArea = document.getElementById('profile-image-area');
    const imageInput = document.getElementById('profile-image-input');
    const imagePreview = document.getElementById('profile-image-preview');

    imageArea.addEventListener('click', function() {
        imageInput.click();
    });

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Flash mesajları için otomatik kapanma
    window.setTimeout(function() {
        document.querySelectorAll(".alert-dismissible").forEach(function(alert) {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 3000);
});
</script>
@endpush
@endsection
