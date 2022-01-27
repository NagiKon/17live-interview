<?php

namespace App\Services;

use App\Exceptions\UndefinedException;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Services\PostService;

class CommentService
{
    private $commentRepository;
    private $postService;

    public function __construct(CommentRepository $commentRepository, PostService $postService)
    {
        $this->commentRepository = $commentRepository;
        $this->postService = $postService;
    }

    public function getCommentById(int $postId, int $commentId): array
    {
        if (!$this->postService->isExistedPost($postId)) {
            throw new UndefinedException("This Post that this comment belongs is not exist. ID: $postId");
        }

        $comment = $this->commentRepository->getCommentById($postId, $commentId);
        if (is_null($comment)) {
            throw new UndefinedException("This Comment is not exist. Comment's ID: $commentId");
        }

        return [
            'id' => $commentId,
            'message' => $comment->message ?? '',
            'createAt' => $comment->created_at,
            'updateAt' => $comment->updated_at,
        ];
    }
}
