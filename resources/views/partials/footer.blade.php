<footer>
    <div class="container">
        <!-- Footer widgets... -->
        <div class="footer-widget">
            <h3>Kategoriler</h3>
            <ul class="footer-links">
                @foreach($kategoriler as $kategori)
                    <li><a href="{{ route('category.show', $kategori->slug) }}">{{ $kategori->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <!-- Diğer footer alanları... -->
        <div class="copyright">
            &copy; {{ now()->year }} HaberPortal. Tüm hakları saklıdır.
        </div>
    </div>
</footer>
