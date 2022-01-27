<?php

namespace App\Repositories;

use App\Models\Comment;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function deleteComment(Comment $comment): void
    {
        $comment->delete();
    }

    public function getCommentListByPostId(int $postId, int $page, int $perPage): LengthAwarePaginator
    {
        return Comment::where('post_id', $postId)->paginate($perPage, '*', '', $page);
    }
}
