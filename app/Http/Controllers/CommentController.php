<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request, News $news)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $this->commentService->storeComment($news, $request->content);

        return back()->with('success', 'Yorumunuz eklendi.');
    }

    public function edit(Comment $comment)
    {
        if (!$this->commentService->userCanModify($comment)) {
            abort(403);
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        $this->commentService->updateComment($comment, $request->content);

        return redirect()->back()->with('success', 'Yorum güncellendi.');
    }

    public function destroy(Comment $comment)
    {
        $this->commentService->deleteComment($comment);

        return back()->with('success', 'Yorum silindi.');
    }
}
