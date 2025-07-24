<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class NewsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $filter = '';

    protected $paginationTheme = 'bootstrap';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteNews($id)
    {
        $news = News::find($id);
        if ($news) {
            $news->delete();
            session()->flash('success', 'Haber başarıyla silindi.');
        }
    }

    public function render()
    {
        $query = News::with(['image', 'category', 'user'])
            ->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
            });

        // Filter by date
        if ($this->filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->filter === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()]);
        }

        $news = $query->orderBy($this->sortField, $this->sortDirection)
                     ->paginate($this->perPage);

        return view('livewire.news-table', [
            'news' => $news
        ]);
    }
}
