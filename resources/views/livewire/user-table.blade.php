<div>
    <!-- Arama ve Filtreler -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <input wire:model.live="search" type="text" class="form-control" placeholder="Kullanıcı ara (ad, email)...">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <select wire:model.live="perPage" class="form-control">
                <option value="10">10 kayıt</option>
                <option value="25">25 kayıt</option>
                <option value="50">50 kayıt</option>
                <option value="100">100 kayıt</option>
            </select>
        </div>
    </div>

    <!-- Tablo -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 60px;">
                        <button wire:click="sortBy('id')" class="btn btn-link p-0 text-decoration-none">
                            ID 
                            @if($sortField === 'id')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </button>
                    </th>
                    <th style="width: 80px;">Profil</th>
                    <th>
                        <button wire:click="sortBy('name')" class="btn btn-link p-0 text-decoration-none">
                            Ad 
                            @if($sortField === 'name')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </button>
                    </th>
                    <th>
                        <button wire:click="sortBy('email')" class="btn btn-link p-0 text-decoration-none">
                            Email 
                            @if($sortField === 'email')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </button>
                    </th>
                    <th>
                        <button wire:click="sortBy('created_at')" class="btn btn-link p-0 text-decoration-none">
                            Kayıt Tarihi 
                            @if($sortField === 'created_at')
                                @if($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </button>
                    </th>
                    <th>Roller</th>
                    <th style="width: 120px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-center">{{ $user->id }}</td>
                        <td class="text-center">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/profile-image/' . $user->profile_image) }}" 
                                     alt="Profil" 
                                     class="rounded-circle" 
                                     width="40" 
                                     height="40">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Rol yok</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn btn-info table-action-btn" 
                                   title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('users.edit')
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-warning table-action-btn" 
                                   title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('users.delete')
                                <button wire:click="deleteUser({{ $user->id }})" 
                                        class="btn btn-danger table-action-btn"
                                        onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')"
                                        title="Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Kullanıcı bulunamadı</h5>
                                <p class="text-muted">Arama kriterlerinizi değiştirmeyi deneyin</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sayfalama -->
    <div class="d-flex justify-content-center mt-4">
        <div class="custom-pagination">
            {{ $users->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('user-deleted', event => {
        alert('Kullanıcı başarıyla silindi!');
    });
</script>
@endpush
