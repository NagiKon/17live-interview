<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;

class CommentRepository
{
    public function getCommentById(int $postId, int $commentId): ?Comment
    {
        return Comment::where('post_id', '=', $postId)->find($commentId);
    }

    public function createComment(int $postId, string $message): void
    {
        $comment = new Comment();

        $comment->post_id = $postId;
        $comment->message = $message;

        $comment->save();
    }

    public function updateComment(Comment $comment, $message): Comment
    {
        $comment->message = $message;
        $comment->save();

        return $comment;
    }
}
