<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haber Portalı | Güncel Haberler</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --text: #333;
            --gray: #95a5a6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: var(--text);
            line-height: 1.6;
        }
        
        /* Header Styles */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .top-bar {
            background-color: var(--primary);
            color: white;
            padding: 8px 0;
            font-size: 0.9rem;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo-section {
            padding: 15px 0;
        }
        
        .logo {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }
        
        .logo span {
            color: var(--accent);
        }
        
        .date-display {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .search-bar {
            display: flex;
            max-width: 400px;
            width: 100%;
        }
        
        .search-bar input {
            flex: 1;
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 4px 0 0 4px;
            font-size: 1rem;
        }
        
        .search-bar button {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        .main-nav {
            background-color: var(--secondary);
        }
        
        .nav-menu {
            list-style: none;
            display: flex;
        }
        
        .nav-menu li {
            position: relative;
        }
        
        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .nav-menu a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1588681664899-f142ff2dc9b1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
        }
        
        .btn {
            display: inline-block;
            background: var(--accent);
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* Main Content */
        .section-title {
            font-size: 1.8rem;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--secondary);
            color: var(--dark);
        }
        
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .news-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card-img {
            height: 200px;
            overflow: hidden;
        }
        
        .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .news-card:hover .card-img img {
            transform: scale(1.05);
        }
        
        .card-content {
            padding: 20px;
        }
        
        .category-tag {
            display: inline-block;
            background: var(--secondary);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        
        .card-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .card-excerpt {
            color: var(--gray);
            margin-bottom: 15px;
        }
        
        .card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        /* Breaking News Ticker */
        .breaking-news {
            background: var(--accent);
            color: white;
            padding: 15px 0;
            margin-bottom: 40px;
        }
        
        .ticker-container {
            display: flex;
            align-items: center;
        }
        
        .ticker-label {
            background: rgba(0,0,0,0.2);
            padding: 5px 15px;
            font-weight: bold;
            margin-right: 20px;
            border-radius: 4px;
        }
        
        .ticker-content {
            flex: 1;
            overflow: hidden;
            position: relative;
            height: 24px;
        }
        
        .ticker-item {
            position: absolute;
            white-space: nowrap;
            animation: ticker 20s linear infinite;
        }
        
        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .footer-widget h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary);
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--secondary);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
            color: #aaa;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 768px) {
            .top-bar .flex {
                flex-direction: column;
                gap: 10px;
            }
            
            .logo-section .flex {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .hero {
                padding: 60px 0;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .ticker-label {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container flex">
            <div class="date-display">
                <i class="fas fa-calendar-alt"></i> <?php echo date('d F Y, l'); ?>
            </div>
            <div class="top-links">
                <a href="#" style="color: white; margin-right: 15px;"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
                <a href="#" style="color: white;"><i class="fas fa-user-plus"></i> Kayıt Ol</a>
            </div>
        </div>
    </div>
    
    <!-- Header -->
    <header>
        <div class="container">
            <div class="logo-section flex">
                <a href="#" class="logo">Haber<span>Portal</span></a>
                <div class="search-bar">
                    <input type="text" placeholder="Haber ara...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="#"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                    <?php
                    // Kategoriler veritabanından çekiliyor
                    $categories = [
                        ['id' => 1, 'name' => 'Gündem'],
                        ['id' => 2, 'name' => 'Ekonomi'],
                        ['id' => 3, 'name' => 'Spor'],
                        ['id' => 4, 'name' => 'Teknoloji'],
                        ['id' => 5, 'name' => 'Sağlık'],
                        ['id' => 6, 'name' => 'Kültür-Sanat'],
                        ['id' => 7, 'name' => 'Dünya']
                    ];
                    
                    foreach ($categories as $category) {
                        echo "<li><a href=\"#\">{$category['name']}</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>
    
    <!-- Breaking News -->
    <div class="breaking-news">
        <div class="container">
            <div class="ticker-container">
                <div class="ticker-label">SON DAKİKA</div>
                <div class="ticker-content">
                    <div class="ticker-item">
                        <?php
                        // Son dakika haberleri veritabanından çekiliyor
                        $breakingNews = [
                            "Merkez Bankası faiz kararını açıkladı! Piyasalar hareketlendi.",
                            "Milli takımımız Dünya Kupası elemelerinde önemli bir galibiyet aldı.",
                            "Yapay zeka alanında devrim yaratacak yeni bir teknoloji duyuruldu."
                        ];
                        
                        foreach ($breakingNews as $index => $news) {
                            echo "<i class=\"fas fa-bolt\"></i> $news";
                            if ($index < count($breakingNews) - 1) {
                                echo "<span style=\"margin: 0 30px;\">|</span>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Dünya'dan ve Türkiye'den En Son Haberler</h1>
            <p>Güncel haberleri, derin analizleri ve özel röportajları takip edin. HaberPort ile dünyanın nabzını tutun.</p>
            <a href="#" class="btn">Abone Ol</a>
        </div>
    </section>
    
    <!-- Main Content -->
    <main class="container">
        <h2 class="section-title">Günün Öne Çıkan Haberleri</h2>
        
        <div class="news-grid">
            <?php
            // Haberler veritabanından çekiliyor
            $featuredNews = [
                [
                    'id' => 1,
                    'title' => 'Bakanlık Yeni Ekonomi Paketini Açıkladı',
                    'excerpt' => 'Hükümetin açıkladığı yeni ekonomi paketi piyasalarda olumlu karşılandı. İşte detaylar...',
                    'category' => 'Ekonomi',
                    'image' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c7e9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                    'created_at' => '2 saat önce',
                    'views' => '1.2K'
                ],
                [
                    'id' => 2,
                    'title' => 'Futbol Transfer Dönemi Sona Erdi: İşte En Büyük Transferler',
                    'excerpt' => 'Yaz transfer dönemi sona erdi. Türk ve dünya futbolunda gerçekleşen en büyük transferler...',
                    'category' => 'Spor',
                    'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                    'created_at' => '5 saat önce',
                    'views' => '3.4K'
                ],
                [
                    'id' => 3,
                    'title' => 'Yerli Elektrikli Otomobil Yollarda: İlk Test Sürüşü Yapıldı',
                    'excerpt' => 'Yerli elektrikli otomobilin test sürüşleri başladı. İşte otomobilin teknik özellikleri ve fiyat aralığı...',
                    'category' => 'Teknoloji',
                    'image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                    'created_at' => '8 saat önce',
                    'views' => '4.1K'
                ]
            ];
            
            foreach ($featuredNews as $news) {
                echo "
                <div class=\"news-card\">
                    <div class=\"card-img\">
                        <img src=\"{$news['image']}\" alt=\"{$news['title']}\">
                    </div>
                    <div class=\"card-content\">
                        <span class=\"category-tag\">{$news['category']}</span>
                        <h3 class=\"card-title\">{$news['title']}</h3>
                        <p class=\"card-excerpt\">{$news['excerpt']}</p>
                        <div class=\"card-meta\">
                            <span><i class=\"far fa-clock\"></i> {$news['created_at']}</span>
                            <span><i class=\"far fa-eye\"></i> {$news['views']}</span>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
        
        <h2 class="section-title">Gündemdeki Haberler</h2>
        
        <div class="news-grid">
            <?php
            // Gündem haberleri veritabanından çekiliyor
            $trendingNews = [
                [
                    'id' => 4,
                    'title' => "BM'den Kritik İklim Zirvesi: Dünya Liderleri Buluştu",
                    'excerpt' => 'Birleşmiş Milletler İklim Zirvesi\'nde dünya liderleri küresel ısınmaya karşı alınacak önlemleri tartışıyor...',
                    'category' => 'Dünya',
                    'image' => 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                    'created_at' => '1 gün önce',
                    'views' => '2.8K'
                ],
                [
                    'id' => 5,
                    'title' => "Uluslararası Film Festivali Başlıyor: Öne Çıkan Filmler",
                    'excerpt' => 'Bu yıl 30.\'su düzenlenen Uluslararası Film Festivali\'nde izleyiciyle buluşacak öne çıkan filmler...',
                    'category' => 'Kültür-Sanat',
                    'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                    'created_at' => '1 gün önce',
                    'views' => '1.7K'
                ],
                [
                    'id' => 6,
                    'title' => "Yeni Kanser Tedavisi Umut Vaat Ediyor: İlk Sonuçlar Olumlu",
                    'excerpt' => 'Bilim insanlarının geliştirdiği yeni kanser tedavisi, klinik denemelerde umut verici sonuçlar verdi...',
                    'category' => 'Sağlık',
                    'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                    'created_at' => '2 gün önce',
                    'views' => '3.9K'
                ]
            ];
            
            foreach ($trendingNews as $news) {
                echo "
                <div class=\"news-card\">
                    <div class=\"card-img\">
                        <img src=\"{$news['image']}\" alt=\"{$news['title']}\">
                    </div>
                    <div class=\"card-content\">
                        <span class=\"category-tag\">{$news['category']}</span>
                        <h3 class=\"card-title\">{$news['title']}</h3>
                        <p class=\"card-excerpt\">{$news['excerpt']}</p>
                        <div class=\"card-meta\">
                            <span><i class=\"far fa-clock\"></i> {$news['created_at']}</span>
                            <span><i class=\"far fa-eye\"></i> {$news['views']}</span>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-widget">
                    <h3>HaberPortal</h3>
                    <p>Türkiye'nin en güvenilir ve güncel haber kaynağı. 2005'ten beri kesintisiz yayın hayatında.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-widget">
                    <h3>Kategoriler</h3>
                    <ul class="footer-links">
                        <?php
                        foreach ($categories as $category) {
                            echo "<li><a href=\"#\">{$category['name']}</a></li>";
                        }
                        ?>
                    </ul>
                </div>
                
                <div class="footer-widget">
                    <h3>Kurumsal</h3>
                    <ul class="footer-links">
                        <li><a href="#">Hakkımızda</a></li>
                        <li><a href="#">İletişim</a></li>
                        <li><a href="#">Reklam</a></li>
                        <li><a href="#">Kullanım Koşulları</a></li>
                        <li><a href="#">Gizlilik Politikası</a></li>
                        <li><a href="#">Abonelik</a></li>
                    </ul>
                </div>
                
                <div class="footer-widget">
                    <h3>Bülten Aboneliği</h3>
                    <p>Günlük haber bültenimize abone olun, en güncel haberler e-posta adresinize gelsin.</p>
                    <div class="search-bar">
                        <input type="email" placeholder="E-posta adresiniz">
                        <button><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> HaberPortal. Tüm hakları saklıdır. Bu site içeriğinin tüm hakları HaberPortal'a aittir.
            </div>
        </div>
    </footer>
    
    <script>
        // Haber kartlarına tıklama efekti
        document.querySelectorAll('.news-card').forEach(card => {
            card.addEventListener('click', function() {
                // Gerçek uygulamada burada haber detay sayfasına yönlendirme yapılır
                const title = this.querySelector('.card-title').textContent;
                alert(`"${title}" haberinin detay sayfasına yönlendiriliyorsunuz...`);
            });
        });
    </script>
</body>
</html>