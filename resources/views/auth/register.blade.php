@extends('layouts.app')

@section('title', 'Kayıt Ol')

@section('content')
<div class="bg-surface-container min-h-[60vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-surface p-8 border-[3px] border-primary flex flex-col gap-6 relative shadow-xl">
        <!-- Accent Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-secondary"></div>

        <div class="text-center mb-2">
            <h2 class="font-display-sm text-display-sm text-primary uppercase mb-2">Hesap Oluştur</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Haberlere yorum yapmak için hemen üye olun</p>
        </div>

        @if(session('success'))
            <div class="bg-secondary-container text-on-secondary-container border border-secondary p-3 text-center font-meta-data text-meta-data">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-error-container text-on-error-container border border-error p-3 text-center font-meta-data text-meta-data">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" class="flex flex-col gap-5">
            @csrf
            
            <div class="flex flex-col gap-2">
                <label for="name" class="font-label-caps text-label-caps text-outline uppercase tracking-wider">Ad Soyad</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-3 bg-surface-container border border-outline-variant text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors font-body-md">
            </div>

            <div class="flex flex-col gap-2">
                <label for="email" class="font-label-caps text-label-caps text-outline uppercase tracking-wider">E-Posta Adresi</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                       class="w-full px-4 py-3 bg-surface-container border border-outline-variant text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors font-body-md">
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="font-label-caps text-label-caps text-outline uppercase tracking-wider">Şifre</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-3 bg-surface-container border border-outline-variant text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors font-body-md">
            </div>

            <div class="flex flex-col gap-2">
                <label for="password_confirmation" class="font-label-caps text-label-caps text-outline uppercase tracking-wider">Şifre Tekrar</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-3 bg-surface-container border border-outline-variant text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors font-body-md">
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-on-primary font-label-large text-label-large py-3 transition-colors uppercase tracking-widest mt-4">
                KAYIT OL
            </button>
        </form>

        <div class="text-center font-meta-data text-meta-data mt-4 pt-4 border-t border-outline-variant text-on-surface-variant">
            Zaten hesabınız var mı? 
            <a href="{{ route('login') }}" class="text-secondary hover:text-secondary-container transition-colors ml-1 font-bold">Giriş Yapın</a>
        </div>

    </div>
</div>
@endsection
