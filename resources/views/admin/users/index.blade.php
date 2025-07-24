@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kullanıcılar</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus fa-sm me-2"></i>Yeni Kullanıcı
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search Form -->
    <div class="search-wrapper">
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col position-relative">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="searchInput"
                                   placeholder="İsim veya e-posta ara..."
                                   autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="clearSearch" style="display: none;">
                                    <i class="fas fa-times fa-sm"></i>
                                </button>
                            </div>
                        </div>
                        <div id="searchResults" class="position-absolute w-100 mt-1 shadow-sm bg-white rounded border" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">Profil</th>
                            <th>İsim</th>
                            <th>E-posta</th>
                            <th>Roller</th>
                            <th>Kayıt Tarihi</th>
                            <th style="width: 120px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/profile-image/' . $user->profile_image) }}" 
                                             alt="{{ $user->name }}" 
                                             class="rounded-circle"
                                             width="40" height="40"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                             style="width: 40px; height: 40px; font-size: 16px;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-primary rounded-pill me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge bg-secondary rounded-pill">Rol Yok</span>
                                    @endforelse
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="btn btn-xs btn-outline-primary"
                                           title="Düzenle">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">
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
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-users fa-2x mb-3 d-block"></i>
                                    Henüz kullanıcı bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Toplam {{ $users->total() }} kullanıcıdan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} arası gösteriliyor
                </div>
                <div>
                    {{ $users->links() }}
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

#searchResults {
    max-height: 300px;
    overflow-y: auto;
    position: absolute;
    width: 100%;
    top: 100%;
    left: 0;
    z-index: 9999;
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.search-result-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #e3e6f0;
    transition: background-color 0.15s ease-in-out;
}

.search-result-item:hover {
    background-color: #f8f9fc;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-title {
    font-weight: 500;
    color: #4e73df;
    margin-bottom: 0.25rem;
}

.search-result-email {
    font-size: 0.875rem;
    color: #858796;
}

.highlight {
    background-color: #fff3cd;
    padding: 0.1rem 0.2rem;
    border-radius: 0.2rem;
}

.card-body {
    position: relative;
    z-index: 1;
}

.table-responsive {
    position: relative;
    z-index: 1;
}

.input-group {
    position: relative;
    z-index: 2;
}

.search-wrapper {
    position: relative;
    z-index: 9999;
}

.rounded-circle {
    border: 2px solid #e3e6f0;
    object-fit: cover;
}

.rounded-circle.bg-secondary {
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 500;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25em 0.6em;
}

.badge.bg-primary {
    background-color: #4e73df !important;
}

.badge.bg-secondary {
    background-color: #858796 !important;
}

.me-1 {
    margin-right: 0.25rem !important;
}

td {
    vertical-align: middle !important;
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
    // Sayfayı temizle
    window.location.href = '{{ route("admin.users.index") }}';
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
    fetch(`{{ route('admin.users.search') }}?search=${encodeURIComponent(searchTerm)}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            selectedIndex = -1; // Reset selection on new results
            
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
            console.error('Search error:', error);
            searchResults.innerHTML = `
                <div class="search-result-item text-danger">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Arama sırasında bir hata oluştu
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
    window.location.href = `{{ route('admin.users.index') }}?search=${encodeURIComponent(title)}`;
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

