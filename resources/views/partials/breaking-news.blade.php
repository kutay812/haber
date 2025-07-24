<div class="breaking-news">
    <div class="container">
        <div class="ticker-container">
            <div class="ticker-label">SON DAKİKA</div>
            <div class="ticker-content">
                <div class="ticker-item">
                    @foreach($breakingNews as $index => $news)
                        <i class="fas fa-bolt"></i> {{ $news->title }}
                        @if(!$loop->last)
                            <span style="margin: 0 30px;">|</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
