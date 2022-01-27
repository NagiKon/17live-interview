<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function getPostById(int $postId): ?Post
    {
        return Post::find($postId);
    }

    public function createPost(string $title, string $content): void
    {
        $post = new Post();

        $post->title = $title;
        $post->content = $content;

        $post->save();
    }
}