import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Theme Management
    initTheme();
    setupThemeToggle();

    // Font Resizing (Reading Experience)
    setupFontResizer();

    // Reading Mode (Sepia toggle)
    setupReadingMode();

    // Lazy Loading fallback if browser doesn't support native lazy-loading
    setupLazyLoading();

    // Reading Progress Bar (Fallback if CSS Timeline scroll() is not supported)
    setupReadingProgressFallback();

    // Mega menu logic
    setupMegaMenu();
});

// Theme Management
function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const preferredTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    const activeTheme = savedTheme || preferredTheme;
    document.documentElement.setAttribute('data-theme', activeTheme);
    updateThemeToggleUI(activeTheme);
}

function setupThemeToggle() {
    const toggleBtns = document.querySelectorAll('.theme-toggle-btn');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

            if (document.startViewTransition) {
                document.startViewTransition(() => {
                    document.documentElement.setAttribute('data-theme', nextTheme);
                    updateThemeToggleUI(nextTheme);
                });
            } else {
                document.documentElement.setAttribute('data-theme', nextTheme);
                updateThemeToggleUI(nextTheme);
            }
            localStorage.setItem('theme', nextTheme);
        });
    });
}

function updateThemeToggleUI(theme) {
    const sunIcons = document.querySelectorAll('.theme-icon-sun');
    const moonIcons = document.querySelectorAll('.theme-icon-moon');
    
    if (theme === 'dark') {
        sunIcons.forEach(i => i.classList.remove('hidden'));
        moonIcons.forEach(i => i.classList.add('hidden'));
    } else {
        sunIcons.forEach(i => i.classList.add('hidden'));
        moonIcons.forEach(i => i.classList.remove('hidden'));
    }
}

// Font Resizing
function setupFontResizer() {
    const increaseBtn = document.getElementById('increase-font');
    const decreaseBtn = document.getElementById('decrease-font');
    const resetBtn = document.getElementById('reset-font');
    const contentArea = document.querySelector('.news-content-area');

    if (!contentArea) return;

    let currentSizePercent = 100;

    if (increaseBtn) {
        increaseBtn.addEventListener('click', () => {
            if (currentSizePercent < 150) {
                currentSizePercent += 10;
                contentArea.style.fontSize = `${currentSizePercent}%`;
            }
        });
    }

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', () => {
            if (currentSizePercent > 80) {
                currentSizePercent -= 10;
                contentArea.style.fontSize = `${currentSizePercent}%`;
            }
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            currentSizePercent = 100;
            contentArea.style.fontSize = '100%';
        });
    }
}

// Reading Mode (Sepia Sepia Mode Toggle)
function setupReadingMode() {
    const readingModeBtn = document.getElementById('reading-mode-toggle');
    const contentBody = document.querySelector('body');
    const articleContainer = document.querySelector('.article-main-container');

    if (readingModeBtn) {
        readingModeBtn.addEventListener('click', () => {
            contentBody.classList.toggle('reading-mode-active');
            if (articleContainer) {
                articleContainer.classList.toggle('shadow-none');
            }
            const isSepia = contentBody.classList.contains('reading-mode-active');
            localStorage.setItem('reading-mode', isSepia ? 'active' : 'inactive');
        });
    }
}

// Lazy Loading fallback
function setupLazyLoading() {
    const lazyImages = document.querySelectorAll('.lazy-image');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const image = entry.target;
                    image.src = image.dataset.src;
                    image.addEventListener('load', () => {
                        image.classList.add('loaded');
                    });
                    imageObserver.unobserve(image);
                }
            });
        });

        lazyImages.forEach(image => {
            imageObserver.observe(image);
        });
    } else {
        // Fallback for older browsers
        lazyImages.forEach(image => {
            image.src = image.dataset.src;
            image.classList.add('loaded');
        });
    }
}

// Reading Progress Bar Fallback (in case CSS Timeline scroll() is not supported)
function setupReadingProgressFallback() {
    const progressBar = document.querySelector('.scroll-progress-bar');
    if (!progressBar) return;

    if (!CSS.supports('animation-timeline', 'scroll()')) {
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.scale = `${scrolled / 100} 1`;
        });
    }
}

// Mega Menu setup
function setupMegaMenu() {
    const megaMenuToggle = document.getElementById('mega-menu-toggle');
    const megaMenu = document.getElementById('mega-menu');
    
    if (megaMenuToggle && megaMenu) {
        megaMenuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            megaMenu.classList.toggle('hidden');
            megaMenu.classList.toggle('flex');
        });

        document.addEventListener('click', (e) => {
            if (!megaMenu.contains(e.target) && e.target !== megaMenuToggle) {
                megaMenu.classList.add('hidden');
                megaMenu.classList.remove('flex');
            }
        });
    }

    // Mobile Menu
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileClose = document.getElementById('mobile-menu-close');
    const mobileBackdrop = document.getElementById('mobile-menu-backdrop');

    if (mobileToggle && mobileMenu) {
        function openMobileMenu() {
            mobileMenu.classList.remove('hidden');
            mobileBackdrop.classList.remove('hidden');
            // Small delay to allow display:block to apply before animating transform/opacity
            setTimeout(() => {
                mobileMenu.classList.remove('-translate-x-full');
                mobileBackdrop.classList.remove('opacity-0');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.add('-translate-x-full');
            mobileBackdrop.classList.add('opacity-0');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
                mobileBackdrop.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300); // matches duration-normal (300ms typically or similar)
        }

        mobileToggle.addEventListener('click', openMobileMenu);
        mobileClose?.addEventListener('click', closeMobileMenu);
        mobileBackdrop?.addEventListener('click', closeMobileMenu);
    }
}
