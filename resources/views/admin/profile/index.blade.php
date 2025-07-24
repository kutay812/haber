@extends('layouts.admin.simple')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Profil Düzenle</h5>
                    <a href="{{ url('/admin') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Geri Dön
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="profile-form" action="{{ url('/admin/profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center mb-3">
                                    <div class="profile-image-wrapper mb-3">
                                        <img id="profile-preview"
                                             src="{{ auth()->user()->profile_image ? asset('storage/profile-image/' . auth()->user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                             class="rounded-circle profile-image"
                                             alt="Profil Resmi">
                                    </div>
                                    <div class="mb-3">
                                        <label for="profile_image" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Resim Seç
                                        </label>
                                        <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
                                    </div>
                                    @if(auth()->user()->profile_image)
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="document.getElementById('remove-image').submit();">
                                            <i class="fas fa-trash me-2"></i>Resmi Kaldır
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Ad Soyad</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">E-posta</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr class="my-4">

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mevcut Şifre</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Yeni Şifre</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Yeni Şifre (Tekrar)</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </form>

                    @if(auth()->user()->profile_image)
                        <form id="remove-image" action="{{ url('/admin/profile/remove-image') }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4" form="profile-form">
                            <i class="fas fa-save me-2"></i>Profili Güncelle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.profile-image-wrapper {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    position: relative;
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border: 2px solid #eee;
}
.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}
.btn-outline-secondary:hover {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Dosya boyutu 2MB\'dan büyük olamaz.');
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
