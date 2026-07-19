<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * Store comment under news
     */
    public function storeComment(News $news, string $content): Comment
    {
        return $news->comments()->create([
            'user_id' => Auth::id(),
            'content' => $content,
        ]);
    }

    /**
     * Update comment with authorization check
     */
    public function updateComment(Comment $comment, string $content): Comment
    {
        if (!$this->userCanModify($comment)) {
            abort(403, 'Bu yorumu düzenlemeye yetkiniz yok.');
        }

        $comment->update(['content' => $content]);
        return $comment;
    }

    /**
     * Delete comment with authorization check
     */
    public function deleteComment(Comment $comment): void
    {
        if (!$this->userCanModify($comment)) {
            abort(403, 'Bu yorumu silmeye yetkiniz yok.');
        }

        $comment->delete();
    }

    /**
     * Check authorization rules
     */
    public function userCanModify(Comment $comment): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Editor'])) {
            return true;
        }

        return $user->id === $comment->user_id;
    }
}
