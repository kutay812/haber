<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gece Haber') | Haber Portal</title>
    <meta name="description" content="@yield('meta_description', 'En güncel ulusal ve uluslararası haberler.')">
    <meta property="og:title" content="@yield('title', 'Gece Haber')">
    <meta property="og:image" content="{{ asset('logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts: Inter & Source Serif 4 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Source+Serif+4:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body x-data="{ mobileMenuOpen: false }" :class="mobileMenuOpen ? 'overflow-hidden' : ''" class="bg-background text-on-background font-body-md min-h-screen flex flex-col">

    <!-- TopNavBar -->
    <header class="bg-surface dark:bg-on-surface docked full-width top-0 border-b border-outline-variant dark:border-on-surface-variant sticky z-50">
        <div class="flex flex-col w-full max-w-[1280px] mx-auto px-[20px] py-2">
            <!-- Top Row: Logo & Search/Actions -->
            <div class="flex justify-between items-center py-4">
                <a class="font-display-hero text-display-hero font-extrabold tracking-tighter text-secondary dark:text-secondary-fixed-dim hover:opacity-80 transition-opacity uppercase" href="{{ route('home') }}">
                    Haber Portal
                </a>
                
                <div class="flex items-center gap-6 hidden md:flex">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <input name="search" value="{{ request('search') }}" class="bg-surface-container border border-outline-variant rounded-full py-2 pl-4 pr-10 focus:outline-none focus:border-primary text-sm w-64" placeholder="Haberlerde ara..." type="text"/>
                        <button type="submit" class="material-symbols-outlined absolute right-3 top-1/2 transform -translate-y-1/2 text-on-surface-variant cursor-pointer">search</button>
                    </form>
                    
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-headline-sm text-headline-sm tracking-tight hover:opacity-80 scale-95 transition-all cursor-pointer">
                                <span class="material-symbols-outlined">person</span> {{ explode(' ', auth()->user()->name)[0] }}
                            </button>
                            <div class="absolute right-0 top-full mt-2 w-48 bg-surface-container-highest border border-outline-variant rounded shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 overflow-hidden">
                                @if(auth()->user()->hasAnyRole(['Admin', 'Editor', 'Super Admin']))
                                    <a href="/admin" class="block px-4 py-2 text-sm text-on-surface hover:bg-surface-container hover:text-secondary"><span class="material-symbols-outlined align-middle text-sm mr-1">settings</span> Yönetim Paneli</a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-container hover:text-error cursor-pointer">
                                        <span class="material-symbols-outlined align-middle text-sm mr-1">logout</span> Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded font-headline-sm text-headline-sm tracking-tight hover:opacity-80 scale-95 transition-all">
                            <span class="material-symbols-outlined">person</span> Giriş Yap
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = true" class="md:hidden text-primary cursor-pointer">
                    <span class="material-symbols-outlined text-3xl">menu</span>
                </button>
            </div>
            
            <!-- Bottom Row: Navigation Links -->
            <nav class="hidden md:flex gap-6 py-2 overflow-x-auto">
                <a class="{{ request()->routeIs('home') && !request()->has('tag') ? 'text-secondary dark:text-secondary-fixed border-b-2 border-secondary dark:border-secondary-fixed' : 'text-on-surface dark:text-surface-variant font-bold hover:text-secondary dark:hover:text-secondary-fixed transition-colors duration-200' }} pb-1 font-headline-sm text-headline-sm tracking-tight whitespace-nowrap" href="{{ route('home') }}">ANA SAYFA</a>
                @foreach(\App\Models\Category::all() as $cat)
                    <a class="{{ request()->is('kategori/'.$cat->slug) ? 'text-secondary dark:text-secondary-fixed border-b-2 border-secondary dark:border-secondary-fixed' : 'text-on-surface dark:text-surface-variant font-bold hover:text-secondary dark:hover:text-secondary-fixed transition-colors duration-200' }} pb-1 font-headline-sm text-headline-sm tracking-tight whitespace-nowrap" href="{{ route('category.news', $cat->slug) }}">{{ mb_strtoupper($cat->name, 'UTF-8') }}</a>
                @endforeach
            </nav>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" style="display: none;" class="fixed inset-0 z-[100] md:hidden">
            <!-- Backdrop -->
            <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            
            <!-- Menu Panel -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full" 
                 class="absolute inset-y-0 left-0 w-4/5 max-w-sm bg-surface dark:bg-surface-container shadow-xl flex flex-col">
                
                <div class="flex items-center justify-between p-4 border-b border-outline-variant">
                    <span class="font-display-hero text-xl font-extrabold uppercase text-secondary">Menü</span>
                    <button @click="mobileMenuOpen = false" class="text-on-surface-variant">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                <div class="p-4 overflow-y-auto flex-1 flex flex-col gap-6">
                    <!-- Mobile Search -->
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        <input name="search" value="{{ request('search') }}" class="w-full bg-surface-container border border-outline-variant rounded-full py-3 pl-4 pr-10 focus:outline-none focus:border-primary text-sm" placeholder="Haberlerde ara..." type="text"/>
                        <button type="submit" class="material-symbols-outlined absolute right-4 top-1/2 transform -translate-y-1/2 text-on-surface-variant">search</button>
                    </form>

                    <!-- Mobile Navigation -->
                    <nav class="flex flex-col gap-4">
                        <a class="text-lg font-bold {{ request()->routeIs('home') && !request()->has('tag') ? 'text-secondary' : 'text-on-surface' }}" href="{{ route('home') }}">ANA SAYFA</a>
                        @foreach(\App\Models\Category::all() as $cat)
                            <a class="text-lg font-bold {{ request()->is('kategori/'.$cat->slug) ? 'text-secondary' : 'text-on-surface' }}" href="{{ route('category.news', $cat->slug) }}">{{ mb_strtoupper($cat->name, 'UTF-8') }}</a>
                        @endforeach
                    </nav>
                </div>

                <!-- Mobile Footer / Auth -->
                <div class="p-4 border-t border-outline-variant bg-surface-container-low">
                    @auth
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2 text-on-surface font-bold">
                                <span class="material-symbols-outlined">account_circle</span>
                                {{ auth()->user()->name }}
                            </div>
                            @if(auth()->user()->hasAnyRole(['Admin', 'Editor', 'Super Admin']))
                                <a href="/admin" class="flex items-center gap-2 text-primary hover:underline">
                                    <span class="material-symbols-outlined text-sm">settings</span> Yönetim Paneli
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 text-error hover:underline w-full text-left">
                                    <span class="material-symbols-outlined text-sm">logout</span> Çıkış Yap
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 bg-primary text-on-primary px-4 py-3 rounded-full font-bold w-full">
                            <span class="material-symbols-outlined">login</span> Giriş Yap
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- BREAKING NEWS TICKER -->
    @yield('breaking_news_ticker')

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="bg-secondary-container text-on-secondary-container border-b-2 border-secondary px-4 py-3 font-headline-sm text-headline-sm text-center shadow-lg relative z-40">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-error-container text-on-error-container border-b-2 border-error px-4 py-3 font-headline-sm text-headline-sm text-center shadow-lg relative z-40">
            {{ session('error') }}
        </div>
    @endif

    <!-- MAIN CONTENT -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-primary dark:bg-on-primary-fixed w-full mt-[48px] py-12 px-[20px] border-t border-primary-fixed-dim">
        <div class="max-w-[1280px] mx-auto flex flex-col items-center gap-6">
            <h2 class="font-headline-lg text-headline-lg text-on-primary font-extrabold tracking-tighter uppercase">Haber Portal</h2>
            <nav class="flex flex-wrap justify-center gap-4">
                <a class="font-body-md text-body-md text-surface-variant dark:text-outline-variant hover:text-white hover:underline transition-all" href="#">Künye</a>
                <a class="font-body-md text-body-md text-surface-variant dark:text-outline-variant hover:text-white hover:underline transition-all" href="#">İletişim</a>
                <a class="font-body-md text-body-md text-surface-variant dark:text-outline-variant hover:text-white hover:underline transition-all" href="#">Gizlilik Politikası</a>
                <a class="font-body-md text-body-md text-surface-variant dark:text-outline-variant hover:text-white hover:underline transition-all" href="#">Kullanım Şartları</a>
            </nav>
            <div class="flex gap-4 mt-4">
                <a href="#" class="w-10 h-10 rounded-full bg-on-primary/10 flex items-center justify-center text-on-primary hover:bg-secondary transition-colors"><span class="material-symbols-outlined">share</span></a>
                <a href="#" class="w-10 h-10 rounded-full bg-on-primary/10 flex items-center justify-center text-on-primary hover:bg-secondary transition-colors"><span class="material-symbols-outlined">mail</span></a>
                <a href="#" class="w-10 h-10 rounded-full bg-on-primary/10 flex items-center justify-center text-on-primary hover:bg-secondary transition-colors"><span class="material-symbols-outlined">subscriptions</span></a>
            </div>
            <p class="font-meta-data text-meta-data text-surface-variant/70 mt-6 text-center">
                © {{ date('Y') }} Haber Portal. Tüm hakları saklıdır.<br>İçerikler izinsiz kopyalanamaz.
            </p>
        </div>
    </footer>

    @yield('structured_data')
</body>
</html>
