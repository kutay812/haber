{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<rss version="2.0" 
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
     xmlns:media="http://search.yahoo.com/mrss/">

<channel>
    <title>{{ $siteName ?? 'Gece Haber' }}</title>
    <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml" />
    <link>{{ url('/') }}</link>
    <description>{{ $siteDesc ?? 'En Güncel Haberler' }}</description>
    <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
    <language>tr-TR</language>
    <sy:updatePeriod>hourly</sy:updatePeriod>
    <sy:updateFrequency>1</sy:updateFrequency>

    @foreach($news as $item)
        <item>
            <title>{{ e($item->title) }}</title>
            <link>{{ route('news.show', $item->slug) }}</link>
            <dc:creator><![CDATA[{{ $item->user ? $item->user->name : ($item->newsSource ? $item->newsSource->name : 'Editör') }}]]></dc:creator>
            <pubDate>{{ $item->created_at->toRssString() }}</pubDate>
            <category><![CDATA[{{ $item->category?->name ?? 'Gündem' }}]]></category>
            <guid isPermaLink="false">{{ route('news.show', $item->slug) }}</guid>
            <description><![CDATA[{{ $item->description }}]]></description>
            <content:encoded><![CDATA[{!! $item->content !!}]]></content:encoded>
            @if($item->image)
                <media:content url="{{ $item->image_url }}" medium="image" width="800" height="450" />
            @endif
        </item>
    @endforeach
</channel>
</rss>
