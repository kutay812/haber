# 🚀 Antigravity News Aggregator

![Antigravity Banner](https://via.placeholder.com/1200x400.png?text=Antigravity+News+Aggregator)

**Antigravity News Aggregator**, internetten güvenilir haberleri çekip, doğrulayıp, görselleriyle birlikte otomatik yayınlayan sistemli, modern ve tam otomatik bir haber portalıdır.

## ✨ Temel Özellikler (Features)

*   **🔄 Otomatik Veri Çekme (Cron/Bot):** RSS feed'lerinden veya belirlenmiş kaynaklardan saatlik/günlük otomatik haber ve manşet çekimi.
*   **🖼 Görsel İşleme:** Haberin kapak fotoğraflarını çekip, formatlayarak sisteme entegre etme.
*   **🛡 XSS Korumalı Veri Temizleme:** Bot ile çekilen haber metinlerindeki tüm zararlı scriptlerin (XSS) engellenmesi için modern blade escaping ve sanitization altyapısı.
*   **🔐 Gelişmiş Admin Paneli & Yetkilendirme:** Spatie tabanlı Role/Permission mimarisi, Superadmin yönetimi ve detaylı yetkilendirme sistemi.
*   **📱 Mobil Uyumluluk (Responsive Design):** Alpine.js ve Tailwind CSS ile tamamen esnek, mobil dostu harika bir kullanıcı arayüzü.
*   **🚀 Performans Odaklı:** Eager loading ve cache stratejileriyle desteklenmiş hızlı veritabanı sorguları.

## 🛠 Kullanılan Teknolojiler (Tech Stack)

*   **Backend:** [Laravel 12] / [PHP 8.2+]
*   **Frontend:** [Tailwind CSS 4.0], [Alpine.js], [Blade Templates]
*   **Veritabanı:** [MySQL] / [SQLite]
*   **Paketler & Güvenlik:** `Spatie/Laravel-Permission`, CSRF ve SQLi önlemleri, şifrelenmiş kimlik denetimi.

## 📦 Kurulum (Installation)

Projeyi bilgisayarınıza kurmak ve yerel geliştirme (local) ortamında çalıştırmak için aşağıdaki adımları izleyin:

**1. Repoyu Klonlayın:**
```bash
git clone https://github.com/KULLANICI_ADINIZ/antigravity-news.git
cd antigravity-news
```

**2. Bağımlılıkları Yükleyin:**
```bash
composer install
npm install && npm run build
```

**3. Ortam Değişkenlerini (Environment Variables) Ayarlayın:**
```bash
cp .env.example .env
php artisan key:generate
```
*(Not: `.env` dosyasına girerek veritabanı bağlantı ayarlarınızı [DB_DATABASE, DB_USERNAME, vb.] yapmayı unutmayın.)*

**4. Veritabanı Kurulumu ve Varsayılan Verilerin Yüklenmesi:**
```bash
php artisan migrate --seed
```
*Gerekli yetkiler ve sahte haber verileri seed edilecektir.*

**5. Projeyi Çalıştırın:**
```bash
php artisan serve
```
Ve `http://localhost:8000` adresinden projenize ulaşabilirsiniz. 

> **Not:** Otomatik haber çekme işlemlerini test etmek için `php artisan schedule:work` komutunu ayrıca çalıştırabilirsiniz.

## 📁 Proje Ağaç Yapısı (Folder Structure)

```text
antigravity-news/
├── app/                  # Controller, Model ve Servis katmanları
│   ├── Http/             # Request'ler ve Controller'lar
│   ├── Services/         # Haber çekme ve Market datası servisleri
│   └── Console/          # Zamanlanmış Görevler (Cron/Scheduler)
├── config/               # Uygulama yapılandırmaları
├── database/             # Veritabanı şemaları (Migrations) ve Seeder'lar
├── public/               # Derlenmiş Frontend dosyaları (CSS/JS)
├── resources/            
│   └── views/            # UI Katmanı (Blade dosyaları, Tailwind yapıları)
├── routes/               # API ve Web rotaları
└── storage/              # [GİZLİ] Log dosyaları ve güvenli medya klasörü
```

## 🛡 Güvenlik Notu

Bu proje, açık kaynak dünyasındaki en iyi pratikler ve **OWASP standartları** göz önüne alınarak geliştirilmiştir.
- SQL Injection (SQLi) koruması için sadece **Prepared Statements** ve Eloquent kullanılmıştır.
- Tüm çıktı işlemlerinde **Reflected & Stored XSS** koruması (HTML escaping) aktiftir.
- Form isteklerinin tamamı **CSRF (Cross-Site Request Forgery)** token'ları ile korunmaktadır.
- Hassas `.env` verileri ve `storage/logs/` klasörleri izole edilmiştir ve asla repoya yüklenmez.

---
*Geliştirme veya destek talepleriniz için Issues sekmesinden kayıt açabilirsiniz.* 🚀
