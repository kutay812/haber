<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Validate;

class Categories extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public string $search = '';

    public function getCategoriesProperty()
    {
        return Category::where('name', 'like', '%' . $this->search . '%')->get();
    }

    public function updatedSearch()
    {
        $this->validate();

        if ($this->categories->isEmpty()) {
            session()->flash('error', 'Sonuç bulunamadı.');
        }
    }

    public function delete(int $id): void
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            session()->flash('success', 'Kategori başarıyla silindi.');
        } else {
            session()->flash('error', 'Kategori bulunamadı.');
        }
    }

    public function render()
    {
        return view('livewire.categories', [
            'categories' => $this->categories
        ]);
    }
}
