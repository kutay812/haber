---
name: Livewire Frontend Expert
description: Livewire 3 komponent mimarisi, reaktif state yönetimi ve Alpine.js entegrasyonu uzmanlığı.
---

# Livewire Frontend Expert Skill

Bu skill, Livewire v3 kullanılarak reaktif ve performanslı arayüzler geliştirmek için gereken standartları belirler.

## Sorumluluklar
- SPA (Single Page Application) benzeri deneyim sunan Livewire komponentlerinin yazılması.
- `Rappasoft Laravel Livewire Tables` veya benzeri paketlerle veri tablolarının oluşturulması.
- Form gönderim, sayfalama ve arama işlemlerinin Livewire üzerinden reaktif olarak tasarlanması.
- İhtiyaç duyulduğunda Livewire ve Alpine.js entegrasyonunun sağlanması (`x-data`, `@entangle`).

## Yönergeler
1. **Komponent Tasarımı**:
   - Mümkünse bileşenleri küçük ve tekrar kullanılabilir parçalara bölün.
   - Blade şablonlarında logic (iş mantığı) barındırmaktan kaçının; tüm logic PHP (Livewire Component) sınıfı içinde olmalıdır.
2. **State ve Performans**:
   - Büyük veritabanı sorgularının sonuçlarını property olarak tutmaktan kaçının (hydration sorunları). Bunun yerine `Computed` property (`#[Computed]`) yapısını kullanın.
   - Sadece gerekli property'leri public olarak tanımlayın, hassas verileri component state'inde açık bırakmayın.
3. **Formlar ve Doğrulama**:
   - Livewire formlarında `Form Objects` (ör. `public LoginForm $form`) yapısını kullanarak state kalabalığını önleyin.
   - Doğrulama işlemlerinde Livewire'ın kendi `#[Validate]` attribute tabanlı doğrulama yeteneklerini kullanın.
4. **Kullanıcı Deneyimi (UX)**:
   - Uzun süren işlemlerde butonları veya yükleme ekranlarını göstermek için `wire:loading` eklentilerini etkin şekilde kullanın.
   - Eylemleri engellemek için `wire:loading.attr="disabled"` ekleyin.
