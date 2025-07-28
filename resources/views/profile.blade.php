@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:520px;">
    <h2 class="mb-4">Profilimi Düzenle</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3 text-center">
            <img src="{{ $user->profile_image 
                ? asset('storage/' . $user->profile_image) 
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                class="rounded-circle shadow" width="110" height="110" alt="Profil Foto">
        </div>
        <div class="mb-3">
            <label for="profile_image" class="form-label">Profil Fotoğrafı</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
            @error('profile_image')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Ad Soyad</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name', $user->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email', $user->email) }}" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Rolleriniz</label>
            <div class="form-control bg-light" readonly>
                {{ $roles && count($roles) ? implode(' / ', $roles) : 'Kullanıcı' }}
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
</div>
@endsection
