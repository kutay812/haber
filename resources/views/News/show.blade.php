@extends('layouts.app')

@section('title', $news->title)
@section('meta_description', Str::limit(strip_tags($news->description ?? $news->content), 150))

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ str_replace('"', '\"', $news->title) }}",
  "image": [
    "{{ $news->image_url }}"
   ],
  "datePublished": "{{ $news->created_at->toIso8601String() }}",
  "dateModified": "{{ $news->updated_at->toIso8601String() }}",
  "author": [{
      "@@type": "Person",
      "name": "Haber Portal"
  }]
}
</script>
@endsection

@section('content')
<main class="max-w-[1280px] mx-auto px-[20px] py-8">
    
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex text-on-surface-variant font-meta-data text-meta-data mb-6">
        <ol class="inline-flex items-center flex-wrap gap-y-1">
            <li class="inline-flex items-center hover:text-primary transition-colors cursor-pointer"><a href="{{ route('home') }}">Ana Sayfa</a></li>
            <li><span class="mx-1 md:mx-2 material-symbols-outlined text-[14px]">chevron_right</span></li>
            @if($news->categories && $news->categories->count() > 0)
                @foreach($news->categories as $index => $cat)
                    <li class="inline-flex items-center hover:text-primary transition-colors cursor-pointer"><a href="{{ route('category.news', $cat->slug) }}">{{ $cat->name }}</a></li>
                    <li><span class="mx-1 md:mx-2 material-symbols-outlined text-[14px]">chevron_right</span></li>
                @endforeach
            @elseif($news->category)
                <li class="inline-flex items-center hover:text-primary transition-colors cursor-pointer"><a href="{{ route('category.news', $news->category->slug) }}">{{ $news->category->name }}</a></li>
                <li><span class="mx-1 md:mx-2 material-symbols-outlined text-[14px]">chevron_right</span></li>
            @endif
            <li aria-current="page" class="inline-flex items-center font-bold text-on-surface cursor-default truncate max-w-[150px] sm:max-w-[300px]">{{ $news->title }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Social Share (Desktop) -->
        <aside class="hidden lg:flex lg:col-span-1 flex-col gap-4 sticky top-32 h-fit items-center">
            <div class="text-outline font-label-caps text-label-caps mb-2 origin-left rotate-[-90deg] whitespace-nowrap mt-12">PAYLAŞ</div>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' ' . request()->url()) }}" target="_blank" class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center hover:opacity-80 transition-opacity" title="WhatsApp">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">chat</span>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" target="_blank" class="w-10 h-10 rounded-full bg-[#1DA1F2] text-white flex items-center justify-center hover:opacity-80 transition-opacity" title="Twitter">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">share</span>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 rounded-full bg-[#4267B2] text-white flex items-center justify-center hover:opacity-80 transition-opacity" title="Facebook">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">thumb_up</span>
            </a>
            <div class="h-12 w-[1px] bg-outline-variant my-2"></div>
            <a href="#comments" class="w-10 h-10 rounded-full border border-outline-variant text-on-surface flex items-center justify-center hover:bg-surface-variant transition-colors" title="Yorumlar">
                <span class="material-symbols-outlined">forum</span>
            </a>
        </aside>

        <!-- Center Column: Article Content -->
        <article class="col-span-1 lg:col-span-8">
            
            @if($news->category)
            <!-- Category Chip -->
            <div class="inline-block bg-primary text-on-primary px-3 py-1 font-label-caps text-label-caps rounded-sm mb-4">
                {{ mb_strtoupper($news->category->name) }}
            </div>
            @endif

            <!-- Headline -->
            <h1 class="font-display-hero-mobile md:font-display-hero text-display-hero-mobile md:text-display-hero text-on-surface mb-4 leading-tight">
                {{ $news->title }}
            </h1>

            <!-- Subtitle (Description) -->
            @if($news->description)
            <p class="font-body-lg text-body-lg text-on-surface-variant italic mb-6 border-l-4 border-secondary pl-4">
                {{ $news->description }}
            </p>
            @endif

            <!-- Meta Data -->
            <div class="flex flex-wrap items-center gap-4 text-on-surface-variant font-meta-data text-meta-data mb-8 pb-4 border-b border-outline-variant">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">newspaper</span>
                    @if($news->newsSource)
                        <a href="{{ $news->source_url }}" target="_blank" rel="noopener noreferrer" class="font-bold text-on-surface hover:text-secondary transition-colors">
                            {{ $news->newsSource->name }}
                        </a>
                        <span class="text-outline font-meta-data text-[11px] bg-surface-container px-2 py-0.5 rounded-full border border-outline-variant">RSS</span>
                    @else
                        <span class="font-bold text-on-surface">Haber Portal</span>
                    @endif
                </div>
                <span class="hidden sm:inline text-outline">•</span>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    <span>{{ $news->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
                @if($news->reading_time)
                <span class="hidden sm:inline text-outline">•</span>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">timer</span>
                    <span>{{ $news->reading_time }} dk okuma süresi</span>
                </div>
                @endif
                <div class="flex items-center gap-1 ml-auto">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    <span>{{ $news->views_count ?? 0 }} Okunma</span>
                </div>
            </div>

            <!-- Featured Image -->
            <figure class="mb-8 w-full">
                <img class="w-full aspect-[16/9] object-cover border border-outline-variant rounded-sm" loading="lazy" src="{{ $news->image_url }}" alt="{{ $news->title }}">
            </figure>

            <!-- Mobile Social Share -->
            <div class="flex lg:hidden gap-4 mb-8 justify-center border-b border-outline-variant pb-6">
                <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' ' . request()->url()) }}" target="_blank" class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">chat</span>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" target="_blank" class="w-10 h-10 rounded-full bg-[#1DA1F2] text-white flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">share</span>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 rounded-full bg-[#4267B2] text-white flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">thumb_up</span>
                </a>
            </div>

            <!-- Body Text -->
            <div class="font-body-md md:font-body-lg text-body-md md:text-body-lg text-on-surface space-y-6 prose prose-lg max-w-none prose-headings:font-headline-md prose-headings:text-primary prose-a:text-secondary hover:prose-a:text-primary transition-colors">
                {!! $news->content !!}
            </div>

            <!-- Tags -->
            @if($news->tags && count($news->tags) > 0)
            <div class="mt-12 flex flex-wrap gap-2">
                <span class="font-label-caps text-label-caps text-outline mb-2 w-full">ETİKETLER:</span>
                @foreach($news->tags as $tag)
                <a href="{{ route('home', ['tag' => $tag->name]) }}" class="bg-surface-container hover:bg-surface-variant px-3 py-1.5 rounded-sm font-meta-data text-meta-data text-on-surface transition-colors border border-outline-variant">
                    #{{ $tag->name }}
                </a>
                @endforeach
            </div>
            @endif
        </article>

        <!-- Yorumlar Bölümü -->
        <section class="col-span-1 lg:col-span-6 mt-12 pt-8 border-t-[3px] border-primary">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-headline-md text-headline-md text-primary uppercase">Yorumlar ({{ $news->comments->count() }})</h2>
            </div>

            <!-- Yorum Formu -->
            <div class="bg-surface-container p-6 mb-8 border border-outline-variant rounded-sm">
                @auth
                    <form action="{{ route('comments.store', $news->id) }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label for="content" class="sr-only">Yorumunuz</label>
                            <textarea name="content" id="content" rows="4" class="w-full px-4 py-3 bg-surface border border-outline text-on-surface focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors font-body-md" placeholder="Habere dair düşüncelerinizi paylaşın..." required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-on-primary font-label-large text-label-large py-2.5 px-6 rounded-sm transition-colors uppercase tracking-wider">
                                Yorum Gönder
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-6">
                        <p class="font-body-lg text-body-lg text-on-surface-variant mb-4">Yorum yapabilmek için üye girişi yapmalısınız.</p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('login') }}" class="bg-primary hover:bg-primary-hover text-on-primary font-label-large text-label-large py-2 px-6 rounded-sm transition-colors uppercase">Giriş Yap</a>
                            <a href="{{ route('register') }}" class="border border-primary text-primary hover:bg-primary hover:text-on-primary font-label-large text-label-large py-2 px-6 rounded-sm transition-colors uppercase">Kayıt Ol</a>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Yorum Listesi -->
            <div class="space-y-6">
                @forelse($news->comments as $comment)
                    <div class="flex gap-4 p-5 bg-surface border border-outline-variant hover:border-secondary transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center font-headline-sm uppercase font-bold">
                                {{ mb_substr($comment->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-baseline mb-2">
                                <h4 class="font-headline-sm text-headline-sm text-on-surface">{{ $comment->user->name }}</h4>
                                <span class="font-meta-data text-[12px] text-outline">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="font-body-md text-body-md text-on-surface-variant whitespace-pre-wrap">{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-surface-container border border-outline-variant border-dashed">
                        <p class="font-body-lg text-body-lg text-on-surface-variant">İlk yorumu siz yapın!</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Right Column: Sidebar -->
        <aside class="col-span-1 lg:col-span-3 space-y-8">
            <!-- Trending List -->
            @if(isset($mostRead) && count($mostRead) > 0)
            <div class="bg-surface p-4 border border-outline-variant">
                <div class="flex items-center gap-2 mb-4 border-b border-primary pb-2">
                    <span class="w-2 h-2 bg-secondary rounded-full"></span>
                    <h2 class="font-headline-sm text-headline-sm text-primary uppercase">Çok Okunanlar</h2>
                </div>
                <ul class="space-y-4">
                    @foreach($mostRead->take(5) as $index => $item)
                    <li class="flex gap-4 items-start group cursor-pointer {{ $index > 0 ? 'border-t border-surface-container-high pt-4' : '' }}">
                        <span class="font-display-hero text-display-hero text-surface-dim group-hover:text-secondary transition-colors leading-none -mt-1">{{ $index + 1 }}</span>
                        <div>
                            <a href="{{ route('news.show', $item->slug) }}">
                                <h4 class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors leading-snug">{{ $item->title }}</h4>
                            </a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Newsletter Signup -->
            <div class="bg-primary text-on-primary p-6 rounded-sm">
                <h3 class="font-headline-sm text-headline-sm mb-2">Gündemi Yakalayın</h3>
                <p class="font-meta-data text-meta-data text-on-primary-container mb-4">En önemli haberler her sabah e-postanızda.</p>
                <form class="flex flex-col gap-2">
                    <input class="px-3 py-2 text-on-surface bg-surface rounded-sm font-meta-data focus:outline-none focus:ring-2 focus:ring-secondary border-none" placeholder="E-posta adresiniz" type="email"/>
                    <button class="bg-secondary text-white font-label-caps text-label-caps py-2 rounded-sm hover:bg-secondary-container transition-colors" type="button">ABONE OL</button>
                </form>
            </div>
        </aside>

    </div>

    <!-- Related News Grid -->
    @if(isset($relatedNews) && count($relatedNews) > 0)
    <section class="mt-16 border-t-[3px] border-primary pt-8">
        <h2 class="font-headline-md text-headline-md text-primary mb-6">BUNLAR DA İLGİNİZİ ÇEKEBİLİR</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedNews as $item)
            <a class="group block border border-outline-variant bg-surface hover:shadow-lg transition-shadow" href="{{ route('news.show', $item->slug) }}">
                <div class="relative w-full aspect-[4/3] overflow-hidden">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" src="{{ $item->image_url }}" alt="{{ $item->title }}">
                </div>
                <div class="p-4">
                    @if($item->category)
                    <span class="text-secondary font-label-caps text-label-caps mb-2 block">{{ mb_strtoupper($item->category->name) }}</span>
                    @endif
                    <h3 class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors line-clamp-3">
                        {{ $item->title }}
                    </h3>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

</main>
@endsection
