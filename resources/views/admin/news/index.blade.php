@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Haberler</h1>
        <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus fa-sm me-2"></i>Yeni Haber
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0 ps-0" 
                               id="searchInput"
                               placeholder="Aramak için yazın..."
                               autocomplete="off">
                        <button class="btn btn-link text-danger" 
                                type="button" 
                                id="clearSearch" 
                                style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="searchResults" class="position-absolute w-100 mt-1 shadow-lg bg-white rounded-lg border-0" style="display: none; z-index: 1050;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="newsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Başlık</th>
                            <th>Kategori</th>
                            <th>Yazar</th>
                            <th>Durum</th>
                            <th>Görüntülenme</th>
                            <th>Tarih</th>
                            <th style="width: 120px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($news as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.news.show', $item) }}" class="text-decoration-none">
                                        {{ Str::limit($item->title, 50) }}
                                    </a>
                                </td>
                                <td>{{ $item->category->name ?? 'Kategorisiz' }}</td>
                                <td>{{ $item->user->name ?? 'Bilinmiyor' }}</td>
                                <td>
                                    @switch($item->status)
                                        @case('published')
                                            <span class="badge bg-success rounded-pill">Yayında</span>
                                            @break
                                        @case('draft')
                                            <span class="badge bg-warning rounded-pill">Taslak</span>
                                            @break
                                        @case('archived')
                                            <span class="badge bg-secondary rounded-pill">Arşivlenmiş</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $item->views }}</td>
                                <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.news.edit', $item) }}" 
                                           class="btn btn-xs btn-outline-primary"
                                           title="Düzenle">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $item) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bu haberi silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-xs btn-outline-danger" 
                                                    title="Sil">
                                                <i class="fas fa-trash fa-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-newspaper fa-2x mb-3 d-block"></i>
                                    Henüz haber bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Toplam {{ $news->total() }} haberden {{ $news->firstItem() ?? 0 }}-{{ $news->lastItem() ?? 0 }} arası gösteriliyor
                </div>
                <div>
                    {{ $news->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.btn-xs {
    padding: 0.2rem 0.4rem;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.25em 0.6em;
}

.table > :not(caption) > * > * {
    padding: 0.75rem;
}

.btn-group {
    display: flex;
    gap: 0.25rem;
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}

.position-relative {
    position: relative !important;
    z-index: 9999;
}

.input-group {
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(32,33,36,.28);
    transition: all .2s ease;
}

.input-group:hover,
.input-group:focus-within {
    box-shadow: 0 1px 8px rgba(32,33,36,.35);
}

.input-group .form-control {
    border: none;
    font-size: 16px;
    padding: 12px 16px;
}

.input-group .form-control:focus {
    box-shadow: none;
}

.input-group-text {
    border: none;
    padding-left: 16px;
}

.input-group .btn-link {
    border: none;
    color: #70757a;
    padding: 0 16px;
    text-decoration: none;
}

#searchResults {
    max-height: calc(100vh - 300px);
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(32,33,36,.28);
}

.search-result-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f4;
    transition: background-color .2s ease;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-title {
    color: #1a73e8;
    font-size: 14px;
    margin-bottom: 4px;
}

.search-result-category {
    color: #70757a;
    font-size: 12px;
}

.highlight {
    background-color: #e8f0fe;
    padding: 0 2px;
    border-radius: 2px;
}

/* Scrollbar Styles */
#searchResults::-webkit-scrollbar {
    width: 8px;
}

#searchResults::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#searchResults::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

#searchResults::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endpush

@push('scripts')
<script>
let searchTimeout = null;
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
const clearSearch = document.getElementById('clearSearch');

searchInput.addEventListener('input', function(e) {
    const searchTerm = e.target.value.trim();
    clearSearch.style.display = searchTerm ? 'block' : 'none';
    
    if (searchTerm.length < 1) {
        searchResults.style.display = 'none';
        return;
    }

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => performSearch(searchTerm), 150);
});

searchInput.addEventListener('focus', function() {
    if (this.value.trim()) {
        searchResults.style.display = 'block';
    }
});

clearSearch.addEventListener('click', function() {
    searchInput.value = '';
    searchResults.style.display = 'none';
    clearSearch.style.display = 'none';
    searchInput.focus();
    window.location.href = '{{ route("admin.news.index") }}';
});

document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target) && !clearSearch.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// Klavye navigasyonu için
let selectedIndex = -1;
searchInput.addEventListener('keydown', function(e) {
    const items = searchResults.getElementsByClassName('search-result-item');
    
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        
        if (e.key === 'ArrowDown') {
            selectedIndex = (selectedIndex + 1) % items.length;
        } else {
            selectedIndex = selectedIndex <= 0 ? items.length - 1 : selectedIndex - 1;
        }
        
        Array.from(items).forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('bg-light');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('bg-light');
            }
        });
    } else if (e.key === 'Enter' && selectedIndex >= 0) {
        e.preventDefault();
        const selectedItem = items[selectedIndex];
        if (selectedItem) {
            const title = selectedItem.querySelector('.search-result-title').textContent;
            selectResult(title);
        }
    } else if (e.key === 'Escape') {
        searchResults.style.display = 'none';
        searchInput.blur();
    }
});

function performSearch(searchTerm) {
    console.log('Arama yapılıyor:', searchTerm);
    const searchUrl = `{{ route('admin.news.search') }}?search=${encodeURIComponent(searchTerm)}`;
    console.log('Arama URL:', searchUrl);

    fetch(searchUrl)
        .then(response => {
            console.log('Sunucu yanıtı:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Arama sonuçları:', data);
            selectedIndex = -1;
            
            if (data.items && data.items.length > 0) {
                const resultsHtml = data.items.map(item => `
                    <div class="search-result-item" onclick="selectResult('${item.text}')">
                        <div class="search-result-title">${highlightText(item.text, searchTerm)}</div>
                        ${item.category ? `<div class="search-result-category">${item.category}</div>` : ''}
                    </div>
                `).join('');
                
                searchResults.innerHTML = resultsHtml;
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = `
                    <div class="search-result-item text-muted">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-search me-2"></i>
                            Sonuç bulunamadı
                        </div>
                    </div>`;
                searchResults.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Arama hatası:', error);
            searchResults.innerHTML = `
                <div class="search-result-item text-danger">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Arama sırasında bir hata oluştu: ${error.message}
                    </div>
                </div>`;
            searchResults.style.display = 'block';
        });
}

function selectResult(title) {
    // Arama kutusuna seçilen başlığı yaz
    searchInput.value = title;
    // Arama sonuçlarını gizle
    searchResults.style.display = 'none';
    // Temizle butonunu göster
    clearSearch.style.display = 'block';
    // Sayfayı filtrele
    window.location.href = `{{ route('admin.news.index') }}?search=${encodeURIComponent(title)}`;
}

function highlightText(text, searchTerm) {
    if (!searchTerm) return text;
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    return text.replace(regex, '<span class="highlight">$1</span>');
}

// Sayfa yüklendiğinde URL'deki search parametresini kontrol et
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) {
        searchInput.value = searchParam;
        clearSearch.style.display = 'block';
    }
});
</script>
@endpush
@endsection
