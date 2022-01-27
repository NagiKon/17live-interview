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
}
