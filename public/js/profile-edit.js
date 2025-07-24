document.addEventListener('DOMContentLoaded', function() {
    const profileImageArea = document.getElementById('profile-image-area');
    const profileImageInput = document.getElementById('profile-image-input');
    const profileImagePreview = document.getElementById('profile-image-preview');
    const changeImageBtn = document.getElementById('change-image-btn');
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');

    function showError(message) {
        alert(message);
        console.error(message);
    }

    // Profil resmi alanına tıklayınca input açılsın
    if (profileImageArea && profileImageInput) {
        profileImageArea.addEventListener('click', function(e) {
            if (e.target !== profileImageInput) {
                e.preventDefault();
                profileImageInput.click();
            }
        });

        // Drag & Drop desteği
        profileImageArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        profileImageArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
        });
        profileImageArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            if (e.dataTransfer && e.dataTransfer.files.length > 0) {
                profileImageInput.files = e.dataTransfer.files;
                profileImageInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }

    // Değiştir butonu ile input açılsın (isteğe bağlı, yoksa kaldır)
    if (changeImageBtn && profileImageInput) {
        changeImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            profileImageInput.click();
        });
        changeImageBtn.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                profileImageInput.click();
            }
        });
    }

    // Dosya seçildiğinde önizleme ve validasyon
    if (profileImageInput && profileImagePreview) {
        profileImageInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) return;

            const maxSize = 10 * 1024 * 1024;
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (file.size > maxSize) {
                showError('Dosya boyutu 10MB\'dan büyük olamaz!');
                this.value = '';
                return;
            }
            if (!allowedTypes.includes(file.type.toLowerCase())) {
                showError('Sadece JPEG, PNG, GIF veya WebP dosyaları yükleyebilirsiniz!');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                profileImagePreview.src = e.target.result;
                profileImagePreview.style.display = 'block';
                profileImagePreview.style.width = '150px';
                profileImagePreview.style.height = '150px';
                profileImagePreview.style.objectFit = 'cover';
                profileImagePreview.style.borderRadius = '50%';
            };
            reader.onerror = function() {
                showError('Dosya okunurken bir hata oluştu!');
                profileImageInput.value = '';
            };
            reader.readAsDataURL(file);
        });
    }

    // Form submit: butonu devre dışı bırak
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showError('Lütfen tüm gerekli alanları doldurun!');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Güncelleniyor...';

            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Güncelle';
                }
            }, 30000);
        });
    }
});
