@if(isset($breakingNews) && $breakingNews->isNotEmpty())
    <section class="bg-accent text-white border-b border-accent-hover/30 shadow-md">
        <div class="max-w-7xl mx-auto flex items-stretch">
            
            <!-- Badge -->
            <div class="bg-neutral-950 font-black text-xs md:text-sm tracking-widest px-6 py-3 flex items-center gap-2 shrink-0 select-none animate-pulse">
                <span class="w-2.5 h-2.5 bg-red-600 rounded-full inline-block animate-ping"></span>
                SON DAKİKA
            </div>

            <!-- Ticker content -->
            <div class="ticker-wrap flex-grow flex items-center relative overflow-hidden text-sm font-semibold py-2">
                <div class="ticker-content-anim inline-block flex items-center gap-12 whitespace-nowrap">
                    @foreach($breakingNews as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="hover:underline flex items-center gap-2 select-none group text-white">
                            <span class="text-xs bg-neutral-950/20 px-1.5 py-0.5 rounded text-white/80 font-normal">
                                {{ $item->category?->name ?? 'Gündem' }}
                            </span>
                            <span class="font-bold text-sm tracking-tight text-white group-hover:text-neutral-200 transition-colors">
                                {{ $item->title }}
                            </span>
                            <i class="fas fa-arrow-right text-xs opacity-60 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            
        </div>
    </section>
@endif
