---
name: Tailwind Design Expert
description: Tailwind CSS 4.0 ile modern, mobil öncelikli, responsive ve erişilebilir arayüz tasarım uzmanlığı.
---

# Tailwind Design Expert Skill

Bu skill, projenin arayüzünü (UI) dünya standartlarında, modern ve erişilebilir (Accessibility) bir tasarımla kodlamak için kullanılır.

## Sorumluluklar
- Blade/Livewire dosyalarındaki HTML elementlerine uygun Tailwind utility class'larını entegre etmek.
- Karanlık Mod (Dark Mode) desteğinin tutarlı bir şekilde sağlanması.
- Bileşenlerin (Buton, Modal, Kart, Tablo vb.) modern, temiz ve estetik standartlara uygun tasarlanması.

## Yönergeler
1. **Modern Tasarım Dili**:
   - Geleneksel sıkıcı tasarımlardan kaçının. Yumuşak gölgeler (`shadow-sm`, `shadow-md`), hafif kenarlık yarıçapları (`rounded-xl`, `rounded-2xl`) ve estetik boşluklar (padding/margin) kullanın.
   - Renk paletlerinde projenin ana rengine uygun (örneğin modern bir indigo, slate veya zinc paleti) renkler kullanın.
2. **Dark Mode**:
   - Uygulamanın tam Dark Mode uyumlu olmasını sağlayın. Her bir arka plan ve metin rengi için bir `dark:` alternatifi ekleyin (Örnek: `bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200`).
3. **Responsive Design (Mobil Öncelikli)**:
   - İlk yazılan class'lar her zaman mobil görünüm içindir.
   - Ekran büyüdükçe (sm, md, lg, xl) farklılık gösteren yapıları ekleyin (Örnek: `flex-col md:flex-row`).
4. **Erişilebilirlik (Accessibility)**:
   - Form alanlarına uygun etiketler (`<label>`) tanımlayın.
   - İkonlu butonlar kullanıyorsanız, `sr-only` class'ını kullanarak ekran okuyucular için açıklayıcı metinler ekleyin.
   - Focus durumlarında (`focus:ring`, `focus:outline-none`) görsel geri bildirimleri belirgin tutun.
