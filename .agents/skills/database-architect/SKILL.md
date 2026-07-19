---
name: Database Architect
description: Veritabanı şema tasarımı, migrasyon yönetimi, ilişkisel tablo optimizasyonları ve indeksleme stratejileri uzmanlığı.
---

# Database Architect Skill

Bu skill, uygulamanın veritabanı katmanının performanslı, ölçeklenebilir ve sağlam (robust) olarak yapılandırılmasını sağlamak içindir.

## Sorumluluklar
- Laravel migrasyon (Migration) dosyalarının standartlara uygun yazılması.
- Haberler, Etiketler, Kategoriler, Yazarlar gibi birbiriyle ilişkili (`One-to-Many`, `Many-to-Many`, `Polymorphic`) veritabanı şemalarının tasarlanması.
- Performans sorunlarını önlemek amacıyla doğru indekslerin (Index) belirlenmesi.
- Factory ve Seeder sınıflarının oluşturularak sahte veri (dummy data) üretiminin organize edilmesi.

## Yönergeler
1. **Şema Tasarımı**:
   - Tablo isimleri her zaman çoğul İngilizce kelimelerden oluşmalıdır (ör. `news`, `tags`, `categories`).
   - Yabancı anahtar (Foreign Key) sütunlarında `constrained()` ve `cascadeOnDelete()` yapılarını aktif kullanın.
2. **Optimizasyon ve İndeksler**:
   - Sıkça "Where" koşulu uygulanan (örneğin `status`, `published_at`, `slug`) sütunlara Index (`$table->index('slug')`) atayın.
   - Benzersiz olması gereken sütunları (örneğin `slug`, `email`) `unique()` olarak işaretleyin.
3. **Standartlar**:
   - String uzunluklarına dikkat edin. Mümkün olduğunca varsayılanları (255) aşacak durumlarda `text`, `longText` kullanın.
   - Her tabloda `timestamps()` (created_at, updated_at) olmalıdır. Silinen kayıtları saklamak gerekiyorsa `softDeletes()` kullanın.
