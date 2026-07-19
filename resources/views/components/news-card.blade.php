@props(['news', 'type' => 'block', 'showCategory' => true, 'showSummary' => false])

@php
    /*
     * Types: 
     * - block: Standard top image, bottom text. Good for grid.
     * - list: Left image, right text. Good for narrow columns or vertical lists.
     * - hero: Large cover image with text overlay at bottom.
     * - hero-sm: Smaller overlay, good for side-by-side with hero.
     * - text-only: No image, just title and date. Good for fast breaking news lists.
     */
@endphp

@if($type === 'hero' || $type === 'hero-sm')
    <a href="{{ route('news.show', $news->slug) }}" class="group relative block w-full h-full overflow-hidden rounded-xl bg-bg-secondary hover-lift">
        <div class="absolute inset-0 skeleton-loading z-0"></div>
        <img src="{{ $news->image_url }}" alt="{{ $news->title }}" loading="lazy" class="w-full h-full object-cover relative z-10 transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-20"></div>
        
        <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6 z-30 flex flex-col justify-end">
            @if($showCategory && $news->category)
                <span class="bg-accent text-white text-[10px] md:text-xs font-bold px-2 py-1 rounded uppercase tracking-wider mb-2 self-start shadow-sm">
                    {{ $news->category->name }}
                </span>
            @endif
            <h3 class="{{ $type === 'hero' ? 'text-xl md:text-3xl' : 'text-lg md:text-xl' }} font-extrabold text-white leading-tight font-heading group-hover:text-accent transition-colors line-clamp-3 drop-shadow-md">
                {{ $news->title }}
            </h3>
            <div class="flex items-center gap-3 mt-2 text-[10px] md:text-xs text-neutral-300 font-medium">
                <span class="flex items-center gap-1.5"><i class="far fa-clock"></i> {{ $news->published_date }}</span>
                @if($news->reading_time && $type === 'hero')
                    <span class="flex items-center gap-1.5 hidden md:flex"><i class="fas fa-book-reader"></i> {{ $news->reading_time }} dk</span>
                @endif
            </div>
        </div>
    </a>

@elseif($type === 'list')
    <a href="{{ route('news.show', $news->slug) }}" class="group flex gap-4 w-full bg-bg-secondary p-3 rounded-xl border border-border hover:border-accent hover-lift transition-all">
        <div class="w-24 md:w-32 shrink-0 aspect-[4/3] rounded-lg overflow-hidden relative">
            <div class="absolute inset-0 skeleton-loading z-0"></div>
            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" loading="lazy" class="w-full h-full object-cover relative z-10 transition-transform duration-500 group-hover:scale-110">
        </div>
        <div class="flex flex-col flex-1 justify-center">
            @if($showCategory && $news->category)
                <span class="text-[10px] font-bold uppercase tracking-wider text-accent mb-1">{{ $news->category->name }}</span>
            @endif
            <h3 class="text-sm md:text-base font-bold text-text leading-snug font-heading group-hover:text-accent transition-colors line-clamp-2">
                {{ $news->title }}
            </h3>
            @if($showSummary)
                <p class="text-xs text-text-muted mt-1.5 line-clamp-1 hidden md:block">{{ Str::limit(strip_tags($news->description ?? $news->content), 80) }}</p>
            @endif
            <span class="text-[10px] text-text-muted mt-2 flex items-center gap-1.5"><i class="far fa-clock"></i> {{ $news->published_date }}</span>
        </div>
    </a>

@elseif($type === 'text-only')
    <a href="{{ route('news.show', $news->slug) }}" class="group flex flex-col gap-1 w-full border-b border-border/50 last:border-0 pb-3 mb-3 last:pb-0 last:mb-0">
        @if($showCategory && $news->category)
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                <span class="text-[10px] font-bold uppercase tracking-wider text-text-secondary">{{ $news->category->name }}</span>
            </div>
        @endif
        <h3 class="text-sm font-semibold text-text leading-snug group-hover:text-accent transition-colors line-clamp-2">
            {{ $news->title }}
        </h3>
        <span class="text-[10px] text-text-muted mt-1"><i class="far fa-clock"></i> {{ $news->published_date }}</span>
    </a>

@else
    {{-- Default: Block Type --}}
    <a href="{{ route('news.show', $news->slug) }}" class="group flex flex-col w-full bg-bg-secondary border border-border rounded-xl overflow-hidden hover-lift transition-all h-full">
        <div class="w-full aspect-video relative overflow-hidden">
            <div class="absolute inset-0 skeleton-loading z-0"></div>
            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" loading="lazy" class="w-full h-full object-cover relative z-10 transition-transform duration-500 group-hover:scale-105">
            @if($news->is_breaking)
                <div class="absolute top-2 left-2 z-20 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider animate-pulse shadow-md">Son Dakika</div>
            @endif
        </div>
        <div class="p-4 flex flex-col flex-1">
            @if($showCategory && $news->category)
                <span class="text-[10px] font-bold uppercase tracking-wider text-accent mb-1.5 block">{{ $news->category->name }}</span>
            @endif
            <h3 class="text-base font-bold text-text leading-snug font-heading group-hover:text-accent transition-colors line-clamp-2">
                {{ $news->title }}
            </h3>
            @if($showSummary)
                <p class="text-xs text-text-muted mt-2 line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($news->description ?? $news->content), 100) }}</p>
            @endif
            
            <div class="mt-auto pt-4 flex items-center justify-between text-[11px] text-text-muted font-medium">
                <span class="flex items-center gap-1.5"><i class="far fa-clock"></i> {{ $news->published_date }}</span>
                @if(isset($news->comments_count) && $news->comments_count > 0)
                    <span class="flex items-center gap-1.5"><i class="far fa-comment-dots"></i> {{ $news->comments_count }}</span>
                @endif
            </div>
        </div>
    </a>
@endif
