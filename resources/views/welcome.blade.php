@extends('layouts.app')

@section('title', 'Anasayfa')

@section('breaking_news_ticker')
@if(isset($breakingNews) && $breakingNews->count() > 0)
<div class="w-full flex items-center bg-on-surface h-10 border-b border-outline-variant relative z-40">
    <div class="bg-secondary text-white font-label-caps text-label-caps px-4 py-2 h-full flex items-center whitespace-nowrap z-10 shrink-0">
        SON DAKİKA
        <span class="w-2 h-2 rounded-full bg-white ml-2 animate-pulse"></span>
    </div>
    <div class="ticker-wrap flex-grow h-full flex items-center">
        <div class="ticker">
            @foreach($breakingNews as $item)
                <a href="{{ route('news.show', $item->slug) }}" class="ticker__item text-white hover:text-secondary whitespace-nowrap">{{ $item->title }}</a>
            @endforeach
            <!-- Seamless loop için kopyası -->
            @foreach($breakingNews as $item)
                <a href="{{ route('news.show', $item->slug) }}" class="ticker__item text-white hover:text-secondary whitespace-nowrap">{{ $item->title }}</a>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@section('content')

@if(request()->filled('search') || isset($category) || request()->filled('tag'))
    <div class="w-full max-w-[1280px] mx-auto px-[20px] mt-6 flex-grow flex flex-col gap-6">
        <div class="bg-surface border-b border-outline-variant py-8 mb-6">
            <h1 class="text-headline-lg font-headline-lg text-on-surface">
                @if(request()->filled('search'))
                    "<span class="text-secondary">{{ request('search') }}</span>" sonuçları
                @elseif(isset($category))
                    {{ mb_strtoupper($category->name, 'UTF-8') }}
                @elseif(request()->filled('tag'))
                    #<span class="text-secondary">{{ mb_strtoupper(request('tag'), 'UTF-8') }}</span>
                @endif
            </h1>
            <p class="text-meta-data text-on-surface-variant mt-2">{{ $news->total() }} haber bulundu</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($news as $item)
                <div class="flex flex-col border border-outline-variant bg-surface hover:shadow-lg transition-shadow duration-300">
                    <div class="h-40 overflow-hidden relative">
                        <img class="w-full h-full object-cover" loading="lazy" src="{{ $item->image_url }}" alt="">
                        @if($item->categories && $item->categories->count() > 0)
                            <div class="absolute top-2 left-2 flex gap-1 flex-wrap">
                                @foreach($item->categories as $cat)
                                    <span class="bg-secondary text-white font-label-caps text-[10px] px-2 py-1">{{ mb_strtoupper($cat->name) }}</span>
                                @endforeach
                            </div>
                        @elseif($item->category)
                            <span class="absolute top-2 left-2 bg-secondary text-white font-label-caps text-label-caps px-2 py-1">{{ mb_strtoupper($item->category->name) }}</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <a href="{{ route('news.show', $item->slug) }}">
                            <h4 class="font-headline-sm text-headline-sm text-on-surface hover:text-secondary transition-colors cursor-pointer mb-2">{{ $item->title }}</h4>
                        </a>
                        <p class="font-body-md text-body-md text-on-surface-variant line-clamp-2">{{ Str::limit(strip_tags($item->description ?? $item->content), 80) }}</p>
                        @if($item->newsSource)
                        <span class="font-meta-data text-[11px] text-outline mt-2 inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">rss_feed</span> {{ $item->newsSource->name }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-on-surface-variant">
                    <h3 class="font-headline-md text-headline-md font-bold">Sonuç bulunamadı</h3>
                </div>
            @endforelse
        </div>
        <div class="mt-8">
            {{ $news->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
@else

<main class="w-full max-w-[1280px] mx-auto px-[20px] mt-6 flex-grow grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Left/Center Column (Hero & News Feed) -->
    <div class="lg:col-span-8 flex flex-col gap-6">
        
        <!-- Hero Section (Bento Grid Style) -->
        @if(isset($featuredNews) && $featuredNews->count() >= 4)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 h-auto md:h-[500px]">
            <!-- Lead Story (Spans 3 cols) -->
            <div class="md:col-span-3 relative group overflow-hidden border border-outline-variant min-h-[300px] md:min-h-0 md:h-full flex flex-col justify-end">
                <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" src="{{ $featuredNews[0]->image_url }}" alt="">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="relative z-10 p-4 md:p-6 w-full">
                    @if($featuredNews[0]->categories && $featuredNews[0]->categories->count() > 0)
                        <div class="flex gap-1 flex-wrap mb-2">
                            @foreach($featuredNews[0]->categories as $cat)
                                <span class="bg-primary text-white font-label-caps text-[10px] px-2 py-1 inline-block">{{ mb_strtoupper($cat->name) }}</span>
                            @endforeach
                        </div>
                    @elseif($featuredNews[0]->category)
                        <span class="bg-primary text-white font-label-caps text-label-caps px-2 py-1 mb-2 inline-block">{{ mb_strtoupper($featuredNews[0]->category->name) }}</span>
                    @endif
                    <a href="{{ route('news.show', $featuredNews[0]->slug) }}">
                        <h1 class="text-2xl md:font-display-hero md:text-display-hero text-white hover:text-primary-fixed-dim transition-colors cursor-pointer leading-tight font-extrabold drop-shadow-md">
                            {{ $featuredNews[0]->title }}
                        </h1>
                    </a>
                </div>
            </div>
            
            <!-- Secondary Stories Stack (Spans 1 col) -->
            <div class="grid grid-cols-2 md:grid-cols-1 gap-2 h-auto md:h-full">
                @foreach($featuredNews->slice(1, 3) as $item)
                <div class="relative group overflow-hidden border border-outline-variant min-h-[160px] md:min-h-0 md:h-full flex flex-col justify-end">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" src="{{ $item->image_url }}" alt="">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 to-transparent"></div>
                    <div class="relative z-10 p-3 w-full">
                        @if($item->categories && $item->categories->count() > 0)
                            <div class="flex gap-1 flex-wrap mb-1">
                                @foreach($item->categories as $cat)
                                    <span class="bg-secondary text-white font-label-caps text-[8px] px-1 py-0.5">{{ mb_strtoupper($cat->name) }}</span>
                                @endforeach
                            </div>
                        @elseif($item->category)
                            <span class="bg-secondary text-white font-label-caps text-[8px] px-1 py-0.5 mb-1 inline-block">{{ mb_strtoupper($item->category->name) }}</span>
                        @endif
                        <a href="{{ route('news.show', $item->slug) }}">
                            <h2 class="font-headline-sm text-headline-sm text-white hover:text-primary-fixed-dim transition-colors cursor-pointer line-clamp-2">{{ $item->title }}</h2>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Manşetler (Headlines Grid) -->
        @if(isset($featuredNews) && $featuredNews->count() > 4)
        <div class="mt-[48px]">
            <div class="border-b-2 border-primary mb-4 pb-2">
                <h3 class="font-headline-md text-headline-md text-primary uppercase">Manşetler</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($featuredNews->slice(4, 9) as $item)
                <div class="flex flex-col border border-outline-variant bg-surface hover:shadow-lg transition-shadow duration-300">
                    <div class="h-40 overflow-hidden relative">
                        <img class="w-full h-full object-cover" loading="lazy" src="{{ $item->image_url }}" alt="">
                        @if($item->categories && $item->categories->count() > 0)
                            <div class="absolute top-2 left-2 flex gap-1 flex-wrap">
                                @foreach($item->categories as $cat)
                                    <span class="bg-secondary text-white font-label-caps text-[10px] px-2 py-1">{{ mb_strtoupper($cat->name) }}</span>
                                @endforeach
                            </div>
                        @elseif($item->category)
                            <span class="absolute top-2 left-2 bg-secondary text-white font-label-caps text-label-caps px-2 py-1">{{ mb_strtoupper($item->category->name) }}</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <a href="{{ route('news.show', $item->slug) }}">
                            <h4 class="font-headline-sm text-headline-sm text-on-surface hover:text-secondary transition-colors cursor-pointer mb-2">{{ $item->title }}</h4>
                        </a>
                        <p class="font-body-md text-body-md text-on-surface-variant line-clamp-2">{{ Str::limit(strip_tags($item->description ?? $item->content), 80) }}</p>
                        @if($item->newsSource)
                        <span class="font-meta-data text-[11px] text-outline mt-2 inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">rss_feed</span> {{ $item->newsSource->name }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- Right Column (Sidebar) -->
    <aside class="lg:col-span-4 flex flex-col gap-6">
        
        <!-- Piyasalar -->
        <div class="bg-surface-container border border-outline-variant p-4">
            <h3 class="font-headline-sm text-headline-sm text-primary mb-3 border-b border-outline-variant pb-2">Piyasalar</h3>
            <div class="grid grid-cols-2 gap-4">
                @if(isset($marketData))
                    @foreach($marketData as $name => $data)
                        @php
                            $isUp = str_starts_with($data['change'], '+');
                            $colorClass = $isUp ? 'text-green-700' : 'text-red-700';
                            $icon = $isUp ? 'arrow_upward' : 'arrow_downward';
                        @endphp
                        <div class="flex flex-col">
                            <span class="font-meta-data text-meta-data text-on-surface-variant">{{ $name }}</span>
                            <div class="flex items-center gap-1 {{ $colorClass }} font-bold">
                                <span>{{ $data['value'] }}</span>
                                <span class="material-symbols-outlined text-sm">{{ $icon }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Çok Okunanlar (Trending List) -->
        <div class="bg-surface border border-outline-variant p-4">
            <h3 class="font-headline-sm text-headline-sm text-secondary mb-4 uppercase flex items-center gap-2">
                <span class="material-symbols-outlined">trending_up</span> Çok Okunanlar
            </h3>
            <ol class="flex flex-col gap-3">
                @foreach($mostRead->take(5) as $index => $item)
                <li class="flex items-start gap-3 group cursor-pointer border-b border-outline-variant pb-2 last:border-0">
                    <span class="font-display-hero text-display-hero text-outline/30 group-hover:text-primary transition-colors leading-none">{{ $index + 1 }}</span>
                    <a href="{{ route('news.show', $item->slug) }}">
                        <h4 class="font-body-md text-body-md font-bold text-on-surface group-hover:text-secondary transition-colors">{{ $item->title }}</h4>
                    </a>
                </li>
                @endforeach
            </ol>
        </div>

    </aside>
</main>

@endif
@endsection
