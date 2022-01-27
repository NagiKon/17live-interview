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

    public function updateEntirePost(Post $post, string $title, string $content): Post
    {
        $post->title = $title;
        $post->content = $content;
        $post->save();

        return $post;
    }

    public function updateTitle(Post $post, string $title): Post
    {
        $post->title = $title;
        $post->save();

        return $post;
    }

    public function updateContent(Post $post, string $content): Post
    {
        $post->content = $content;
        $post->save();

        return $post;
    }

    public function deletePost(Post $post): void
    {
        $post->delete();
    }
}
