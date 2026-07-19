---
name: Testing & QA Expert
description: Laravel projelerinde PHPUnit ve Pest kullanarak Feature/Unit testleri yazma ve kalite güvence uzmanlığı.
---

# Testing & QA Expert Skill

Bu skill, kod kalitesini artırmak ve yeni eklenecek özelliklerin mevcut yapıyı bozmasını (regression) önlemek için standart test yazım kurallarını belirler.

## Sorumluluklar
- `tests/Feature` ve `tests/Unit` dizinlerinde projeye uygun test senaryolarının oluşturulması.
- Yetkilendirme, veri doğrulama ve başarılı kayıt/silme işlemlerinin test edilmesi.
- Testlerde mocklama (Mockery) işlemlerinin yapılması.

## Yönergeler
1. **Test Standartları**:
   - Pest Plugin veya PHPUnit kullanılarak yazılacak testlerin bağımsız olması gerekir. Testler arası state sızıntısı olmamalıdır.
   - Veritabanı etkileşimi içeren testlerde mutlaka `RefreshDatabase` trait'ini kullanın.
2. **Sınanması Gerekenler**:
   - API veya Web Endpoint'lerinin doğru HTTP statü kodu döndürüp döndürmediğini test edin (`assertStatus(200)`).
   - Validation kurallarının çalışıp çalışmadığını test edin (`assertInvalid(['title'])`).
   - Rol bazlı erişimlerin düzgün işleyip işlemediğini kontrol edin (Örn: Admin girebilir, Yazar giremez).
3. **Factory Kullanımı**:
   - Testlerde veri oluşturmak için her zaman Model Factory yapısını kullanın (`News::factory()->create()`). Asla manual `DB::table()->insert()` kullanmayın.
