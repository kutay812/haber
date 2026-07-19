<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderByDesc('usage_count')->paginate(15);
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:tags,name',
            'color' => 'required|string|size:7',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Etiket başarıyla eklendi.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:tags,name,' . $tag->id,
            'color' => 'required|string|size:7',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Etiket başarıyla güncellendi.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Etiket başarıyla silindi.');
    }
}
