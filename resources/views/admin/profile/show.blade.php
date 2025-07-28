@extends('admin.layouts.app')

@section('title', 'Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profil Bilgileri</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Ad Soyad</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>E-posta</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Profil Resmi</label>
                            <div class="custom-file">
                                <input type="file" name="profile_image" class="custom-file-input @error('profile_image') is-invalid @enderror" id="profile_image">
                                <label class="custom-file-label" for="profile_image">Dosya seç...</label>
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold">Şifre Değiştir</h6>
                        <small class="text-muted mb-3 d-block">Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakın.</small>

                        <div class="form-group">
                            <label>Mevcut Şifre</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Yeni Şifre</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Yeni Şifre Tekrar</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profil Resmi</h6>
                </div>
                <div class="card-body text-center">
                    @if($user->profile_image)
                        <img src="{{ $user->profile_image_url }}"
                             alt="{{ $user->name }}"
                             class="img-profile rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="img-profile rounded-circle mb-3 bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                             style="width: 150px; height: 150px; font-size: 48px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h5 class="mb-0">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>

                    @foreach($user->roles as $role)
                        <span class="badge badge-primary">{{ $role->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    var label = e.target.nextElementSibling;
    label.innerHTML = fileName;
});
</script>
@endpush
@endsection
