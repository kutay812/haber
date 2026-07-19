---
name: Laravel Backend Expert
description: Laravel 12 mimarisinde backend geliştirme, servis/repository pattern ve güvenli kod yazma uzmanlığı.
---

# Laravel Backend Expert Skill

Bu skill, Laravel 12 projelerinde kaliteli, güvenli ve sürdürülebilir backend kodu yazmak için gereken standartları ve talimatları içerir.

## Sorumluluklar
- Gelen HTTP isteklerini ele alan Controller'ların yazılması.
- İş mantığının (Business Logic) Controller yerine Service ve Action sınıflarında konumlandırılması.
- Veri erişim katmanı için Eloquent ORM kullanarak Model sınıflarının oluşturulması.
- Form doğrulama (Validation) işlemlerinin `FormRequest` sınıflarında yapılması.
- `Spatie\Permission` kullanarak Rol ve Yetki yönetimi entegrasyonlarının tasarlanması.
- API Endpoint'leri ve kaynaklarının (Resource) oluşturulması.

## Yönergeler
1. **Modern PHP**: PHP 8.2 ve üzerinin sunduğu `readonly` sınıfları, `enum` yapılarını ve constructor property promotion özelliklerini her zaman kullanın.
2. **Güvenlik**:
   - `Route::post/put/delete` rotalarında her zaman CSRF koruması olmalıdır. (Blade içinde `@csrf`).
   - XSS açıklarını engellemek için kullanıcı girdilerini doğrulayın.
   - Her Controller metodunda yetki kontrolü yapın (`$this->authorize()`, `Gate`, veya middleware kullanarak).
3. **Model Yapısı**:
   - İlişkileri (`hasMany`, `belongsTo`, vs.) explicit (açıkça) olarak type-hint ile tanımlayın.
   - `protected $fillable` dizi tanımını her zaman ekleyin, asla doğrudan `Request::all()` ile oluşturma yapmayın.
4. **Hata Yönetimi**: Hataları sessizce yutmayın, `Log::error()` ile uygun şekilde loglayın. Kullanıcıya dönen API cevaplarında standart JSON yapıları (ör. `{'status': 'error', 'message': '...'}`) kullanın.
