<section>
    <h2 class="section-title">Günün Öne Çıkan Haberleri</h2>
    <div class="news-grid">
        @foreach($featuredNews as $news)
            @include('components.news-card', ['news' => $news])
        @endforeach
    </div>
</section>
