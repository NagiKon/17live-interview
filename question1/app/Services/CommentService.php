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
        $this->checkPostExistence($postId);
        $comment = $this->getCommentModel($postId, $commentId);

        return [
            'id' => $commentId,
            'message' => $comment->message ?? '',
            'createAt' => $comment->created_at,
            'updateAt' => $comment->updated_at,
        ];
    }

    public function createComment(int $postId, string $message): void
    {
        $this->checkPostExistence($postId);
        $this->commentRepository->createComment($postId, $message);
    }

    public function updateComment(int $postId, int $commentId, string $message): array
    {
        $this->checkPostExistence($postId);

        $comment = $this->commentRepository->updateComment(
            $this->getCommentModel($postId, $commentId),
            $message
        );

        return [
            'id' => $commentId,
            'message' => $comment->message ?? '',
            'createAt' => $comment->created_at,
            'updateAt' => $comment->updated_at,
        ];
    }

    private function checkPostExistence(int $postId): void
    {
        if (!$this->postService->isExistedPost($postId)) {
            throw new UndefinedException("This Post that this comment belongs is not exist. Post's ID: $postId");
        }
    }

    public function deleteCommentById(int $postId, int $commentId): void
    {
        $this->checkPostExistence($postId);

        $comment = $this->getCommentModel($postId, $commentId);
        $this->commentRepository->deleteComment($comment);
    }

    private function getCommentModel(int $postId, int $commentId): Comment
    {
        $comment = $this->commentRepository->getCommentById($postId, $commentId);
        if (is_null($comment)) {
            throw new UndefinedException("This Comment is not exist. Comment's ID: $commentId");
        } else {
            return $comment;
        }
    }
}
