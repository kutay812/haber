@extends('layouts.app')

@section('title', '404 - Sayfa Bulunamadı')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center bg-bg-primary px-4">
    <div class="max-w-2xl w-full text-center space-y-8">
        {{-- 404 Graphic --}}
        <div class="relative inline-block select-none">
            <h1 class="text-[150px] md:text-[200px] font-extrabold text-border leading-none font-heading tracking-tighter opacity-30">
                404
            </h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-accent text-white px-4 py-2 rounded-lg font-bold text-xl md:text-2xl tracking-wider shadow-xl transform -rotate-6">
                    SAYFA BULUNAMADI
                </div>
            </div>
        </div>

        {{-- Text Content --}}
        <div class="space-y-4 relative z-10">
            <h2 class="text-2xl md:text-3xl font-bold text-text">Aradığınız habere veya sayfaya ulaşılamıyor.</h2>
            <p class="text-text-muted text-lg max-w-lg mx-auto">
                Sayfa kaldırılmış, adı değiştirilmiş veya geçici olarak kullanılamıyor olabilir. Aşağıdaki arama kutusunu kullanarak aradığınız içeriği bulabilirsiniz.
            </p>
        </div>

        {{-- Search & Actions --}}
        <div class="max-w-md mx-auto pt-6 space-y-6">
            <form action="{{ route('home') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="Haberlerde ara..." 
                       class="w-full pl-5 pr-14 py-4 rounded-xl bg-bg-secondary border-2 border-border text-text focus:outline-none focus:border-accent transition-colors shadow-inner text-lg">
                <button type="submit" class="absolute right-3 top-2.5 bg-accent hover:bg-accent-hover text-white w-10 h-10 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('home') }}" class="px-8 py-3 bg-accent text-white font-bold rounded-lg hover:bg-accent-hover transition-colors flex items-center gap-2 shadow-lg shadow-accent/20">
                    <i class="fas fa-home"></i> Ana Sayfaya Dön
                </a>
                <button onclick="window.history.back()" class="px-8 py-3 bg-bg-secondary text-text font-bold rounded-lg border border-border hover:bg-border transition-colors flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Geri Dön
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
