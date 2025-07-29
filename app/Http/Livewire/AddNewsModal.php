<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class AddNewsModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $title;
    public $category_id;
    public $description;
    public $content;
    public $image;

    public function render()
    {
        return view('livewire.add-news-modal', [
            'categories' => Category::all()
        ]);
    }

    public function open()
    {
        $this->reset(['title', 'category_id', 'description', 'content', 'image']);
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function save()
    {
        if (!Auth::user()->hasAnyRole(['super-admin', 'admin', 'editor'])) {
            abort(403, 'Bu işlemi yapmaya yetkiniz yok.');
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'image' => 'nullable|image|max:10240',
        ]);

        $news = new News();
        $news->title = $this->title;
        $news->category_id = $this->category_id;
        $news->description = $this->description;
        $news->content = $this->content;
        $news->user_id = Auth::id();

        if ($this->image) {
            $path = $this->image->store('news-images', 'public');
            $news->image = $path;
        }

        $news->save();

        session()->flash('success', 'Haber başarıyla eklendi!');
        $this->close();
        $this->emit('newsAdded');
    }
}
