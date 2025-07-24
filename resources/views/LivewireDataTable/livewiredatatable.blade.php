<div>
    <input wire:model.live="search" type="search" class="form-control" placeholder="Kullanıcı ara..." />
    <table class="table">
        <thead>
            <tr>
                @foreach ($columns as $column => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if ($items->isEmpty())
                <tr>
                    <td colspan="{{ count($columns) }}">
                        <div class="w-100 text-center p-1 alert alert-danger">Kayıt bulunamadı.</div>
                    </td>
                </tr>
            @else
                @foreach ($items as $item)
                    <tr>
                        @foreach ($columns as $column => $label)
                            @php
                                // $column = db kolon adı (ör: name, created_at vs.)
                                $value = $item->$column ?? null;
                            @endphp

                            @if (Str::endsWith($column, '_at') && $value)
                                <td>
                                    <span title="{{ $value }}">
                                        {{ \Illuminate\Support\Carbon::parse($value)->format('d/m/Y H:i:s') }}
                                    </span>
                                </td>
                            @elseif (Str::endsWith($column, '_id'))
                                @php
                                    $relation = null;
                                    if ($value) {
                                        $relationName = Str::camel(Str::beforeLast($column, '_id'));
                                        $relationObj = $item->$relationName ?? null;
                                        $relation = $relationObj;
                                    }
                                @endphp

                                @if (Str::contains($column, 'image'))
                                    <td>
                                        <img src="{{ @$relation?->path }}" alt="{{ @$relation?->name }}" width="100">
                                    </td>
                                @else
                                    <td>{{ @$relation?->name }}</td>
                                @endif
                            @elseif ($column === 'actions')
                                <td>
                                    <div class="btn-group w-100">
                                        @can("$model_name-show")
                                            <form action="{{ $api_route }}/{{ $item->id }}/" method="get" class="formajax_view w-100">
                                                @csrf
                                                <button type="submit" class="w-100 btn btn-sm btn-info p-1">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can("$model_name-edit")
                                            <form action="{{ $api_route }}/{{ $item->id }}/edit" method="get" class="formajax_edit w-100">
                                                @csrf
                                                <button type="submit" class="w-100 btn btn-block btn-sm btn-warning p-1">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can("$model_name-delete", $item)
                                            <form action="{{ $api_route }}/{{ $item->id }}" method="post" class="formajax_delete w-100">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="w-100 btn btn-sm btn-danger p-1">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            @else
                                <td>
                                    {{-- Uzun metinleri kısalt --}}
                                    {{ is_string($value) ? Str::limit($value, 100) : $value }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="pagination pagination-sm">
        @if ($items->previousPageUrl())
            <button wire:click="setPage({{ $items->currentPage() - 1 }})" class="page-item page-link">Önceki</button>
        @endif

        @foreach (range(1, $items->lastPage()) as $page)
            <button wire:click="setPage({{ $page }})"
                class="page-item page-link {{ $items->currentPage() == $page ? 'active' : '' }}">
                {{ $page }}
            </button>
        @endforeach

        @if ($items->nextPageUrl())
            <button wire:click="setPage({{ $items->currentPage() + 1 }})" class="page-item page-link">Sonraki</button>
        @endif
    </div>
</div>
