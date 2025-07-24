<div class="news-card">
    <div class="card-img">
        <img src="{{ $news->image }}" alt="{{ $news->title }}">
    </div>
    <div class="card-content">
        <span class="category-tag">{{ $news->category->name ?? $news->category }}</span>
        <h3 class="card-title">{{ $news->title }}</h3>
        <p class="card-excerpt">{{ $news->excerpt ?? Str::limit($news->content, 100) }}</p>
        <div class="card-meta">
            <span><i class="far fa-clock"></i> {{ $news->created_at->diffForHumans() }}</span>
            <span><i class="far fa-eye"></i> {{ $news->views }}</span>
        </div>
    </div>
</div>
