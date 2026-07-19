{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Homepage -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Categories -->
    @foreach($categories as $category)
        <url>
            <loc>{{ route('category.news', $category->slug) }}</loc>
            <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    <!-- News Articles -->
    @foreach($news as $item)
        <url>
            <loc>{{ route('news.show', $item->slug) }}</loc>
            <lastmod>{{ $item->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
