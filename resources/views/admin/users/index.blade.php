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
                                    <img src="{{ $user->profile_image_url }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle"
                                         width="40" height="40"
                                         style="object-fit: cover;">
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
/* ... styles burada aynı şekilde kalacak ... */
</style>
@endpush

@push('scripts')
<script>
// ... js kodun olduğu gibi kalacak ...
</script>
@endpush
@endsection
