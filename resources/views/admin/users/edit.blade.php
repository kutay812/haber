@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kullanıcı Düzenle</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $user->name }}</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Ad Soyad</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">E-posta</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Yeni Şifre</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Şifreyi değiştirmek için doldurun">
                    <small class="form-text text-muted">Şifreyi değiştirmek istemiyorsanız boş bırakın.</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Şifre Tekrar</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Şifreyi tekrar girin">
                </div>

                @if(auth()->user()->hasRole('Super Admin'))
                    <div class="form-group">
                        <label>Roller</label>
                        <div class="alert alert-info">
                            En az bir rol seçilmelidir. Super Admin rolü sadece diğer Super Admin'ler tarafından atanabilir.
                        </div>
                        <div class="row">
                            @foreach($roles as $role)
                                <div class="col-md-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="role_{{ $role->id }}" 
                                               name="roles[]" 
                                               value="{{ $role->id }}"
                                               {{ (old('roles') && in_array($role->id, old('roles'))) || $user->hasRole($role->name) ? 'checked' : '' }}
                                               {{ $role->name === 'Super Admin' && !auth()->user()->hasRole('Super Admin') ? 'disabled' : '' }}
                                               {{ $user->id === auth()->id() && $role->name === 'Super Admin' && $user->hasRole('Super Admin') ? 'disabled' : '' }}>
                                        <label class="custom-control-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                            @if($role->name === 'Super Admin')
                                                <i class="fas fa-shield-alt text-danger" title="Bu rol sadece Super Admin'ler tarafından atanabilir"></i>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kaydet
                    </button>

                    @if(auth()->user()->hasRole('Super Admin') && auth()->id() !== $user->id)
                        <button type="button" 
                                class="btn btn-danger float-right" 
                                onclick="deleteUser({{ $user->id }})">
                            <i class="fas fa-trash"></i> Kullanıcıyı Sil
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@if(auth()->user()->hasRole('Super Admin'))
    <form id="delete-form" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        function deleteUser(userId) {
            if (confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
    @endpush
@endif
@endsection 