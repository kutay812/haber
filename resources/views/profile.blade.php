@extends('layouts.app')

@section('title', 'Profilimi Düzenle')

@section('content')
<div class="bg-bg-secondary min-h-[70vh] flex items-center justify-center py-12 px-4 transition-colors duration-normal">
    <div class="max-w-md w-full bg-bg-elevated p-8 rounded-2xl border border-border shadow-lg transition-colors flex flex-col gap-6">
        
        <div class="text-center">
            <h2 class="text-2xl font-extrabold text-text tracking-tight font-heading">Profil Ayarları</h2>
            <p class="text-xs text-text-muted mt-1">Hesap bilgilerinizi güncelleyin</p>
        </div>

        @if(session('success'))
            <div class="bg-success/15 border border-success/35 text-success text-xs p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <!-- Profile Image Preview -->
            <div class="flex flex-col items-center gap-2 mb-2">
                <img src="{{ $user->profile_image 
                    ? asset('storage/' . $user->profile_image) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3576f6&color=fff' }}"
                    class="rounded-full border-4 border-accent object-cover w-24 h-24 shadow-md transition-transform hover:scale-105" alt="Profil Foto">
                
                <span class="text-xs text-text-muted font-bold uppercase tracking-wide">Profil Fotoğrafı</span>
            </div>

            <!-- Upload new image -->
            <div>
                <label for="profile_image" class="text-xs text-text-secondary font-bold mb-1.5 block">Yeni Fotoğraf Yükle</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*"
                       class="w-full text-xs text-text-secondary file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-border file:text-xs file:font-semibold file:bg-bg-secondary file:text-text hover:file:bg-border transition-colors">
                @error('profile_image')
                    <small class="text-accent text-[10px] mt-1 block font-bold">{{ $message }}</small>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="text-xs text-text-secondary font-bold mb-1.5 block">Ad Soyad</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                       class="w-full px-3.5 py-2 rounded-lg bg-bg-secondary border border-border text-sm text-text focus:outline-none focus:border-primary-500 transition-colors">
                @error('name')
                    <small class="text-accent text-[10px] mt-1 block font-bold">{{ $message }}</small>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="text-xs text-text-secondary font-bold mb-1.5 block">E-Posta Adresi</label>
                <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                       class="w-full px-3.5 py-2 rounded-lg bg-bg-secondary border border-border text-sm text-text focus:outline-none focus:border-primary-500 transition-colors">
                @error('email')
                    <small class="text-accent text-[10px] mt-1 block font-bold">{{ $message }}</small>
                @enderror
            </div>

            <!-- User Roles Info -->
            <div>
                <label class="text-xs text-text-secondary font-bold mb-1.5 block">Yetkileriniz</label>
                <div class="w-full px-3.5 py-2 rounded-lg bg-bg-secondary/40 border border-border/80 text-sm text-text-secondary select-none font-semibold uppercase tracking-wider text-xs">
                    {{ $roles && count($roles) ? implode(' / ', $roles) : 'Kullanıcı' }}
                </div>
            </div>

            <button type="submit" class="bg-accent hover:bg-accent-hover text-white py-2.5 rounded-lg text-sm font-bold transition-colors mt-2 cursor-pointer">
                Değişiklikleri Kaydet
            </button>
        </form>

    </div>
</div>
@endsection
