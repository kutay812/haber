<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, News $news)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $news->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Yorumunuz eklendi.');
    }

    public function edit(Comment $comment)
    {
        if (!$this->userCanModify($comment)) {
            abort(403);
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if (!$this->userCanModify($comment)) {
            abort(403);
        }

        $request->validate(['content' => 'required|string|max:1000']);

        $comment->update(['content' => $request->content]);

        return redirect()->back()->with('success', 'Yorum güncellendi.');
    }

    public function destroy(Comment $comment)
    {
        if (!$this->userCanModify($comment)) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Yorum silindi.');
    }

    /**
     * Kullanıcının yorumu düzenleme veya silme yetkisini kontrol eder.
     */
    private function userCanModify(Comment $comment): bool
    {
        $user = Auth::user();

        // Eğer kullanıcı Super Admin, Admin veya Editor ise tüm yorumlara erişebilir
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Editor'])) {
            return true;
        }

        // Değilse sadece kendi yorumunu düzenleyebilir/silebilir
        return $user->id === $comment->user_id;
    }
}
