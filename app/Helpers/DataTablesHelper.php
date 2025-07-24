<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
class DataTablesHelper
{
    private Model $model;
    public array $fillable;
    protected array $protectedColumns = [
        'password', 
        'remember_token', 
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    private string $route;
    private string $method;
    private string $tableId;
    private array $customColumns = [];
    private bool $showActions = true;
    private array $actionButtons = ['edit', 'delete'];

    public function __construct(Model $model, string $route = '', string $method = 'POST', string $tableId = 'dataTable')
    {
        $this->model = $model;
        $this->fillable = ['id'] + array_diff($model->getFillable(), $this->protectedColumns);
        $this->route = $route;
        $this->method = $method;
        $this->tableId = $tableId;
    }

    /**
     * Özel kolonlar eklemek için
     */
    public function addCustomColumn(string $name, string $data, ?callable $render = null): self
    {
        $this->customColumns[$name] = [
            'data' => $data,
            'render' => $render
        ];
        return $this;
    }

    /**
     * Aksiyon butonlarını yapılandır
     */
    public function setActionButtons(array $buttons): self
    {
        $this->actionButtons = $buttons;
        return $this;
    }

    /**
     * Aksiyon kolonunu göster/gizle
     */
    public function showActions(bool $show = true): self
    {
        $this->showActions = $show;
        return $this;
    }

    /**
     * Korumalı kolonları güncelle
     */
    public function setProtectedColumns(array $columns): self
    {
        $this->protectedColumns = $columns;
        $this->fillable = ['id'] + array_diff($this->model->getFillable(), $this->protectedColumns);
        return $this;
    }

    public function api(Request $request): array
    {
        $draw = intval($request->get('draw'));
        $start = intval($request->get('start'));
        $length = intval($request->get('length'));
        $search = $request->get('search')['value'] ?? '';
        $order = $request->get('order', []);

        $query = $this->model->query();
        
        // Toplam kayıt sayısını al
        $totalRecords = $query->count();

        // Arama filtresi
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                foreach ($this->fillable as $column) {
                    if ($this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), $column)) {
                        $q->orWhere($column, 'like', '%' . $search . '%');
                    }
                }
            });
        }

        // Filtrelenmiş kayıt sayısını al
        $filteredRecords = $query->count();

        // Sıralama
        if (!empty($order)) {
            foreach ($order as $o) {
                $columnIndex = intval($o['column']);
                if (isset($this->fillable[$columnIndex])) {
                    $column = $this->fillable[$columnIndex];
                    $dir = $o['dir'] === 'desc' ? 'desc' : 'asc';
                    $query->orderBy($column, $dir);
                }
            }
        } else {
            // Varsayılan sıralama
            $query->orderBy('id', 'desc');
        }

        // Sayfalama
        $data = $query->skip($start)->take($length)->get();

        return [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }

    public function createTable(): string
    {
        $columns = $this->fillable;
        $table = '<table class="table table-bordered table-striped dt-responsive nowrap" id="' . $this->tableId . '" style="width:100%">';
        $table .= '<thead>';
        $table .= '<tr>';
        
        // Normal kolonlar
        foreach ($columns as $column) {
            $displayName = $this->getDisplayName($column);
            $table .= '<th>' . $displayName . '</th>';
        }
        
        // Özel kolonlar
        foreach ($this->customColumns as $name => $config) {
            $table .= '<th>' . $name . '</th>';
        }
        
        // Aksiyon kolonu
        if ($this->showActions) {
            $table .= '<th class="text-center">İşlemler</th>';
        }
        
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody></tbody>';
        $table .= '</table>';
        
        return $table;
    }

    public function createJs(): string
    {
        $columns = $this->fillable;
        $js = '<script>
        $(document).ready(function() {
            $("#' . $this->tableId . '").DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ajax": {
                    "url": "' . $this->route . '",
                    "type": "' . $this->method . '",
                    "headers": {
                        "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
                    }
                },
                "columns": [';
                
        // Normal kolonlar
        foreach ($columns as $column) {
            $js .= '{ 
                "data": "' . $column . '",
                "name": "' . $column . '",
                "render": function (data, type, row) {';
                
            if ($column === 'id') {
                $js .= 'return "<a href=\'/admin/' . strtolower(class_basename($this->model)) . 's/\' + row.id + \'/show\'>" + data + "</a>";';
            } else {
                $js .= 'return data || "-";';
            }
            
            $js .= '}
            },';
        }
        
        // Özel kolonlar
        foreach ($this->customColumns as $name => $config) {
            $js .= '{
                "data": "' . $config['data'] . '",
                "name": "' . $config['data'] . '",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row) {
                    return data || "-";
                }
            },';
        }
        
        // Aksiyon kolonu
        if ($this->showActions) {
            $js .= '{
                "data": "id",
                "name": "actions",
                "orderable": false,
                "searchable": false,
                "className": "text-center",
                "render": function (data, type, row) {
                    let buttons = "";';
                    
            if (in_array('edit', $this->actionButtons)) {
                $js .= 'buttons += "<a href=\'/admin/' . strtolower(class_basename($this->model)) . 's/\' + data + \'/edit\' class=\'btn btn-sm btn-primary me-1\'><i class=\'fas fa-edit\'></i></a>";';
            }
            
            if (in_array('delete', $this->actionButtons)) {
                $js .= 'buttons += "<button onclick=\'deleteRecord(\' + data + \')\' class=\'btn btn-sm btn-danger\'><i class=\'fas fa-trash\'></i></button>";';
            }
            
            $js .= 'return buttons;
                }
            }';
        }
        
        $js .= '],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/tr.json"
                },
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "order": [[0, "desc"]]
            });
        });
        
        function deleteRecord(id) {
            if (confirm("Bu kaydı silmek istediğinizden emin misiniz?")) {
                $.ajax({
                    url: "/admin/' . strtolower(class_basename($this->model)) . 's/" + id,
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
                    },
                    success: function(response) {
                        $("#' . $this->tableId . '").DataTable().ajax.reload();
                        // Başarı mesajı göster
                        if (typeof toastr !== "undefined") {
                            toastr.success("Kayıt başarıyla silindi!");
                        }
                    },
                    error: function(xhr) {
                        // Hata mesajı göster
                        if (typeof toastr !== "undefined") {
                            toastr.error("Kayıt silinirken hata oluştu!");
                        }
                    }
                });
            }
        }
        </script>';
        
        return $js;
    }

    /**
     * Kolon adını görüntüleme için düzenle
     */
    private function getDisplayName(string $column): string
    {
        $displayNames = [
            'id' => 'ID',
            'name' => 'Ad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'status' => 'Durum',
            'created_at' => 'Oluşturma Tarihi',
            'updated_at' => 'Güncelleme Tarihi'
        ];

        return $displayNames[$column] ?? ucfirst(str_replace('_', ' ', $column));
    }
}