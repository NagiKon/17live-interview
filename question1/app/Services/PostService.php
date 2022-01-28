<?php

namespace App\Services;

use App\Exceptions\ActionException;
use App\Models\Post;
use App\Repositories\PostRepository;

class PostService
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    private function getPostModel(int $postId): Post
    {
        $post = $this->postRepository->getPostById($postId);
        if (is_null($post)) {
            throw new ActionException(ActionException::ERROR_POST_NOT_EXISTS, "Post's ID: $postId");
        } else {
            return $post;
        }
    }

    public function getPostById(int $postId): array
    {
        $post = $this->getPostModel($postId);

        return [
            'id' => $post->id,
            'title' => $post->title ?? '',
            'content' => $post->content ?? '',
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

    /**
     * 各別更新文章的欄位
     *
     * @param  int         $postId  文章的 ID
     * @param  string|null $title   如果參數為 null，則不更新這個欄位的值。
     * @param  string|null $content 如果參數為 null，則不更新這個欄位的值。
     *
     * @return array 更新過後的文章資訊。
     */
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

    public function deletePostById(int $postId): void
    {
        $post = $this->getPostModel($postId);
        $this->postRepository->deletePost($post);
    }

    public function isExistedPost(int $postId): bool
    {
        try {
            $this->getPostModel($postId);
            return true;
        } catch (ActionException $e) {
            return false;
        }
    }
}
