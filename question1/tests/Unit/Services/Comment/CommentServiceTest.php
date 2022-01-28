<?php

namespace Tests\Unit\Services\Comment;

use App\Exceptions\ActionException;
use App\Models\Comment;
use App\Services\CommentService;
use App\Services\PostService;
use App\Repositories\CommentRepository;

use \Illuminate\Database\Eloquent\Collection as EloquentCollection;
use \Illuminate\Pagination\LengthAwarePaginator as paginator;

use Tests\TestCase;

class CommentServiceTest extends TestCase
{
    public function test_getCommentById_success(): void
    {
        $postId    = 1;
        $commentId = 1;
        $message   = 'test';

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('getCommentById')
                          ->with($postId, $commentId)
                          ->andReturn($this->getTestCommentModel($postId, $commentId, $message));

        $commentService = new CommentService($commentRepository, $postService);
        $result = $commentService->getCommentById($postId, $commentId);

        $this->assertSame($commentId, $result['id']);
        $this->assertSame($message,   $result['message']);
    }

    public function test_belongedPostNotExist_getCommentById_failure(): void
    {
        $postId    = 1;
        $commentId = 1;

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnFalse();

        $commentRepository = $this->mock(CommentRepository::class);

        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->getCommentById($postId, $commentId);
    }

    public function test_belongedPostExist_commentNotExist_getCommentById_failure(): void
    {
        $postId    = 1;
        $commentId = 1;

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('getCommentById')
                    ->with($postId, $commentId)
                    ->andReturnNull();

        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->getCommentById($postId, $commentId);
    }

    public function test_getCommentListByPostId_success(): void
    {
        $postId        = 1;
        $commentId     = 1;
        $message       = 'test';
        $totalComments = 3;
        $page          = 2;
        $perPage       = 1;

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('getCommentListByPostId')
                          ->with($postId, $page, $perPage)
                          ->andReturn(
                              new paginator(EloquentCollection::make([
                                  $this->getTestCommentModel($postId, $commentId,     $message),
                                  $this->getTestCommentModel($postId, $commentId + 1, $message),
                                  $this->getTestCommentModel($postId, $commentId + 2, $message),
                              ]), $totalComments, $perPage, $page)
                          );

        $commentService = new CommentService($commentRepository, $postService);
        $result = $commentService->getCommentListByPostId($postId, $page, $perPage);

        $this->assertSame($totalComments, count($result['commentList']));
        $this->assertSame($page, $result['paginationInfo']['currentPage']);
        $this->assertTrue($result['paginationInfo']['nextPage']);
        $this->assertTrue($result['paginationInfo']['previousPage']);
    }

    public function test_belongedPostNotExist_getCommentListByPostId_failure(): void
    {
        $postId  = 1;
        $page    = 2;
        $perPage = 1;

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnFalse();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->getCommentListByPostId($postId, $page, $perPage);
    }

    public function test_createComment_success(): void
    {
        $postId  = 1;
        $message = 'test';

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('createComment')
                    ->with($postId, $message)
                    ->andReturnNull();

        $commentService = new CommentService($commentRepository, $postService);
        $commentService->createComment($postId, $message);
    }

    public function test_belongedPostNotExist_createComment_failure(): void
    {
        $postId  = 1;
        $message = 'test';

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnFalse();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->createComment($postId, $message);
    }

    public function test_updateComment_success(): void
    {
        $postId           = 1;
        $commentId        = 1;
        $message          = 'test';
        $testCommentModel = $this->getTestCommentModel($postId, $commentId, '');

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('getCommentById')
                          ->with($postId, $commentId)
                          ->andReturn($testCommentModel);

        $commentRepository->shouldReceive('updateComment')
                          ->with($testCommentModel, $message)
                          ->andReturn($this->getTestCommentModel($postId, $commentId, $message));

        $commentService = new CommentService($commentRepository, $postService);
        $result = $commentService->updateComment($postId, $commentId, $message);

        $this->assertSame($commentId, $result['id']);
        $this->assertSame($message,   $result['message']);
    }

    public function test_belongedPostNotExist_updateComment_failure(): void
    {
        $postId    = 1;
        $commentId = 1;
        $message   = 'test';

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnFalse();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->updateComment($postId, $commentId, $message);
    }

    public function test_belongedPostExist_commentNotExist_updateComment_failure(): void
    {
        $postId    = 1;
        $commentId = 1;
        $message   = 'test';

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('getCommentById')
                          ->with($postId, $commentId)
                          ->andReturnNull();

        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->updateComment($postId, $commentId, $message);
    }

    public function test_deleteCommentById_success(): void
    {
        $postId           = 1;
        $commentId        = 1;
        $message          = 'test';
        $testCommentModel = $this->getTestCommentModel($postId, $commentId, $message);

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('getCommentById')
                          ->with($postId, $commentId)
                          ->andReturn($testCommentModel);

        $commentRepository->shouldReceive('deleteComment')
                          ->with($testCommentModel)
                          ->andReturnNull();

        $commentService = new CommentService($commentRepository, $postService);
        $commentService->deleteCommentById($postId, $commentId);
    }

    public function test_belongedPostNotExist_deleteCommentById_failure(): void
    {
        $postId    = 1;
        $commentId = 1;

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnFalse();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->deleteCommentById($postId, $commentId);
    }

    public function test_belongedPostExist_commentNotExist_deleteCommentById_failure(): void
    {
        $postId    = 1;
        $commentId = 1;

        $postService = $this->mock(PostService::class);
        $postService->shouldReceive('isExistedPost')
                    ->with($postId)
                    ->andReturnTrue();

        $commentRepository = $this->mock(CommentRepository::class);
        $commentRepository->shouldReceive('getCommentById')
                          ->with($postId, $commentId)
                          ->andReturnNull();

        $commentService = new CommentService($commentRepository, $postService);

        $this->expectException(ActionException::class);

        $commentService->deleteCommentById($postId, $commentId);
    }

    private function getTestCommentModel(int $postId, int $commentId, string $message): Comment
    {
        $comment = new Comment();

        $comment->post_id = $postId;
        $comment->id      = $commentId;
        $comment->message = $message;

        return $comment;
    }
}
