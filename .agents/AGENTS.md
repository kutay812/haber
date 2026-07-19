# Project Workspace Rules: Haber Portalı (Laravel 12)

Bu dosya, "Haber Portalı" projesi için temel kuralları tanımlar. Proje genelinde çalışırken aşağıdaki kurallara kesinlikle uyulmalıdır.

## 1. Kod Standartları (PHP 8.2+ & Laravel 12)
- **Modern PHP Özellikleri:** Sadece modern PHP 8.2 ve üzeri yetenekleri kullanılmalıdır (`readonly classes`, `match expressions`, `constructor property promotion`, `typed properties` vb.).
- **Strict Typing:** Dosyaların başına `declare(strict_types=1);` eklenmesi tercih edilmelidir. Type hint'ler (parametre ve dönüş tipleri) istisnasız her metoda eklenmelidir.
- **Standartlar:** PSR-12 kodlama standartlarına veya Laravel Pint kurallarına uyulacaktır.
- **Fat Model, Skinny Controller:** İş mantığı Model'ler, Service sınıfları veya Repository'ler içinde yer almalı; Controller'lar olabildiğince ince tutulmalıdır.

## 2. Güvenlik (Security First)
- **Doğrulama (Validation):** Tüm HTTP istekleri (GET, POST, PUT, DELETE) `FormRequest` sınıfları üzerinden doğrulanmalıdır. Asla `$request->all()` doğrudan veritabanına kaydedilmemelidir.
- **Mass Assignment:** Model sınıflarında mass assignment zafiyetlerini önlemek için `$fillable` veya `$guarded` mutlaka tanımlanmalıdır.
- **Yetkilendirme:** Rotalar ve Controller'lar üzerinde mutlaka yetki kontrolleri (`Spatie\Permission` yetenekleri, Middleware veya Policy'ler) uygulanmalıdır.
- **Güvenli Çıktı:** Kullanıcı girdileri Blade veya Livewire view'lerinde her zaman escape edilerek (`{{ $var }}`) ekrana basılmalıdır. XSS açıklarına dikkat edilmelidir.

## 3. Performans ve Optimizasyon
- **Eager Loading:** Veritabanı sorgularında N+1 problemlerinden kaçınmak için `with()`, `load()` veya `withCount()` gibi eager loading metotları kullanılmalıdır.
- **Önbellekleme (Caching):** Sık erişilen ancak nadir değişen veriler (ör. ana sayfa haberleri, popüler etiketler) Redis / Cache facade ile önbelleğe alınmalıdır.
- **Queue System:** Mail gönderimi, dış API çağrıları veya resim optimizasyonları gibi zaman alan işlemler senkron değil, Job (Kuyruk) yapısı kullanılarak asenkron olarak işlenmelidir.

## 4. Frontend & UI Kuralları
- **Tailwind CSS 4.0:** Arayüz bileşenlerinde Tailwind utility class'ları kullanılmalıdır. Custom CSS yazmak yerine Tailwind'in yetenekleri (theme config, arbitary values) tercih edilmelidir.
- **Responsive & Dark Mode:** Tüm arayüzler mobil öncelikli (mobile-first) tasarlanmalı ve tam Dark Mode desteğine sahip olmalıdır.
- **Erişilebilirlik (A11y):** Form elemanlarında, butonlarda ve etkileşimli alanlarda ARIA tag'leri ve `sr-only` class'ları kullanılarak erişilebilirlik standartlarına uyulmalıdır.

## 5. Test Kuralları
- Yazılan her yeni özellik (Feature) için mutlaka otomatik test yazılmalıdır.
- Testlerde `Pest` veya `PHPUnit 11` kullanılmalıdır.
- Veritabanı testlerinde `RefreshDatabase` trait'i kullanılmalı ve `FakerPHP` ile Factory tanımları eksiksiz yapılmalıdır.
