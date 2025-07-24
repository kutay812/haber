<div>
    <!-- Arama ve Filtreler -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <input wire:model.live="search" type="text" class="form-control" placeholder="Haber ara (başlık, açıklama)...">
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
                    <th style="width: 100px;">
                        <button wire:click="sortBy('id')" class="btn btn-link p-0 text-decoration-none">
                            Resim
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
                    <th>
                        <button wire:click="sortBy('title')" class="btn btn-link p-0 text-decoration-none">
                            Başlık
                            @if($sortField === 'title')
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
                        <button wire:click="sortBy('category_id')" class="btn btn-link p-0 text-decoration-none">
                            Kategori
                            @if($sortField === 'category_id')
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
                        <button wire:click="sortBy('user_id')" class="btn btn-link p-0 text-decoration-none">
                            Yazar
                            @if($sortField === 'user_id')
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
                        <button wire:click="sortBy('views')" class="btn btn-link p-0 text-decoration-none">
                            Görüntülenme
                            @if($sortField === 'views')
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
                            Tarih
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
                    <th style="width: 120px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    <tr>
                        <td class="text-center">
                            <img src="{{ $item->image ? asset('storage/profile-image/'.$item->image->path) : asset('images/no-image.jpg') }}" 
                                 alt="{{ $item->title }}" 
                                 class="img-thumbnail"
                                 style="max-width: 80px;">
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category->name ?? 'Kategorisiz' }}</td>
                        <td>{{ $item->user->name ?? 'Bilinmiyor' }}</td>
                        <td>{{ $item->views }}</td>
                        <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.news.show', $item) }}" 
                                   class="btn btn-info table-action-btn" 
                                   title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.news.edit', $item) }}" 
                                   class="btn btn-warning table-action-btn" 
                                   title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @can('news.delete')
                                <button wire:click="deleteNews({{ $item->id }})" 
                                        class="btn btn-danger table-action-btn"
                                        onclick="return confirm('Bu haberi silmek istediğinizden emin misiniz?')"
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
                                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Haber bulunamadı</h5>
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
            {{ $news->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('news-deleted', event => {
        alert('Haber başarıyla silindi!');
    });
</script>
@endpush
 