{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    @foreach($news as $item)
        <url>
            <loc>{{ route('news.show', $item->slug) }}</loc>
            <news:news>
                <news:publication>
                    <news:name>{{ $siteName ?? 'Gece Haber' }}</news:name>
                    <news:language>tr</news:language>
                </news:publication>
                <news:publication_date>{{ $item->created_at->toIso8601String() }}</news:publication_date>
                <news:title>{{ e($item->title) }}</news:title>
            </news:news>
        </url>
    @endforeach
</urlset>
