<?php

namespace App\Services;

use App\Exceptions\UndefinedException;
use App\Repositories\PostRepository;

class PostService
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPostById(int $postId): array
    {
        $post = $this->postRepository->getPostById($postId);
        if (is_null($post)) {
            throw new UndefinedException("This Post is not exist. ID: $postId");
        }

        return [
            'id' => $post->id,
            'title' => $post->title ?? '',
            'content' => $post-> content ?? '',
            'createAt' => $post->created_at,
            'updateAt' => $post->updated_at,
        ];
    }
}
