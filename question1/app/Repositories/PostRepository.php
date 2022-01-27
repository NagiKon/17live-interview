<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function getPostById(int $postId): ?Post
    {
        return Post::find($postId);
    }
}