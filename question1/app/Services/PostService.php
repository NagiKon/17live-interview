<?php

namespace App\Services;

use App\Exceptions\UndefinedException;
use App\Models\Post;
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
        $post = $this->getPostModel($postId);

        return [
            'id' => $post->id,
            'title' => $post->title ?? '',
            'content' => $post-> content ?? '',
            'createAt' => $post->created_at,
            'updateAt' => $post->updated_at,
        ];
    }

    public function createPost(string $title, string $content): void
    {
        $this->postRepository->createPost($title, $content);
    }

    public function updateEntirePostById(int $postId, string $title, string $content): array
    {
        $post = $this->postRepository->updateEntirePost(
            $this->getPostModel($postId),
            $title,
            $content
        );

        return [
            'id' => $post->id,
            'title' => $post->title ?? '',
            'content' => $post->content ?? '',
            'createAt' => $post->created_at,
            'updateAt' => $post->updated_at,
        ];
    }

    public function updatePostById(int $postId, ?string $title, ?string $content): array
    {
        $post = $this->getPostModel($postId);
        if (isset($title)) {
            $post = $this->postRepository->updateTitle($post, $title);
        }

        if (isset($content)) {
            $post = $this->postRepository->updateContent($post, $content);
        }

        return [
            'id' => $post->id,
            'title' => $post->title ?? '',
            'content' => $post->content ?? '',
            'createAt' => $post->created_at,
            'updateAt' => $post->updated_at,
        ];
    }

    private function getPostModel(int $postId): Post
    {
        $post = $this->postRepository->getPostById($postId);
        if (is_null($post)) {
            throw new UndefinedException("This Post is not exist. ID: $postId");
        } else {
            return $post;
        }
    }

    public function deletePostById(int $postId): void
    {
        $post = $this->postRepository->getPostById($postId);
        if (isset($post)) {
            $this->postRepository->deletePost($post);
        }
    }
}
