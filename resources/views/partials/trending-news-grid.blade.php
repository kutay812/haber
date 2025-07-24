<section>
    <h2 class="section-title">Gündemdeki Haberler</h2>
    <div class="news-grid">
        @foreach($trendingNews as $news)
            @include('components.news-card', ['news' => $news])
        @endforeach
    </div>
</section>
