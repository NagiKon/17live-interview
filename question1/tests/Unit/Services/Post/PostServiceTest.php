<?php

namespace Tests\Unit\Services\Post;

use App\Exceptions\ActionException;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Services\PostService;

use Tests\TestCase;

class PostServiceTest extends TestCase
{
    public function test_getPostById_success(): void
    {
        $postId  = 1;
        $title   = 'test-title';
        $content = 'test-content';

        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturn($this->getTestPostModel($postId, $title, $content));

        $postService = new PostService($postRepository);
        $result = $postService->getPostById($postId);

        $this->assertSame($postId,  $result['id']);
        $this->assertSame($title,   $result['title']);
        $this->assertSame($content, $result['content']);
    }

    public function test_postNotExist_getPostById_failure(): void
    {
        $postId = 1;
        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturnNull();

        $postService = new PostService($postRepository);

        $this->expectException(ActionException::class);

        $postService->getPostById($postId);
    }

    public function test_createPost_success(): void
    {
        $title = 'test-title';
        $content = 'test-content';

        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('createPost')->with($title, $content)->andReturnNull();

        $postService = new PostService($postRepository);
        $postService->createPost($title, $content);
    }

    public function test_updateEntirePostById_success(): void
    {
        $postId        = 1;
        $title         = 'test-title';
        $content       = 'test-content';
        $testPostModel = $this->getTestPostModel($postId, '', '');

        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturn($testPostModel);

        $postRepository->shouldReceive('updateEntirePost')
                       ->with($testPostModel, $title, $content)
                       ->andReturn($this->getTestPostModel($postId, $title, $content));

        $postService = new PostService($postRepository);
        $result = $postService->updateEntirePostById($postId, $title, $content);

        $this->assertSame($postId,  $result['id']);
        $this->assertSame($title,   $result['title']);
        $this->assertSame($content, $result['content']);
    }

    public function test_postNotExist_updateEntirePostById_failure(): void
    {
        $postId  = 1;
        $title   = 'test-title';
        $content = 'test-content';

        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturnNull();

        $postService = new PostService($postRepository);

        $this->expectException(ActionException::class);

        $postService->updateEntirePostById($postId, $title, $content);
    }

    public function test_updatePostById_success(): void
    {
        $postId  = 1;
        $title   = 'test-title';
        $content = '';

        $testPostModel  = $this->getTestPostModel($postId, '', $content);
        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturn($testPostModel);

        $postRepository->shouldReceive('updateTitle')
                       ->with($testPostModel, $title)
                       ->andReturn($this->getTestPostModel($postId, $title, $content));

        $postService = new PostService($postRepository);
        $result = $postService->updatePostById($postId, $title, null);

        $this->assertSame($postId,  $result['id']);
        $this->assertSame($title,   $result['title']);
        $this->assertSame($content, $result['content']);
    }

    public function test_postNotExist_updatePostById_failure(): void
    {
        $postId = 1;
        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturnNull();

        $postService = new PostService($postRepository);

        $this->expectException(ActionException::class);

        $postService->updatePostById($postId, null, null);
    }

    public function test_deletePostById_success(): void
    {
        $postId = 1;
        $testPostModel = $this->getTestPostModel($postId, '', '');

        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturn($testPostModel);

        $postRepository->shouldReceive('deletePost')
                       ->with($testPostModel)
                       ->andReturnNull();

        $postService = new PostService($postRepository);
        $postService->deletePostById($postId);
    }

    public function test_postNotExist_deletePostById_failure(): void
    {
        $postId = 1;
        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturnNull();

        $postService = new PostService($postRepository);

        $this->expectException(ActionException::class);

        $postService->deletePostById($postId);
    }

    public function test_isExistedPost_success()
    {
        $postId  = 1;
        $title   = 'test-title';
        $content = 'test-content';

        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturn($this->getTestPostModel($postId, $title, $content));

        $postService = new PostService($postRepository);
        $this->assertTrue($postService->isExistedPost($postId));
    }

    public function test_isExistedPost_failure()
    {
        $postId = 1;
        $postRepository = $this->mock(PostRepository::class);
        $postRepository->shouldReceive('getPostById')
                       ->with($postId)
                       ->andReturnNull();

        $postService = new PostService($postRepository);
        $this->assertFalse($postService->isExistedPost($postId));
    }

    private function getTestPostModel(int $postId, string $title, string $content): Post
    {
        $testPostModel = new Post();

        $testPostModel->id      = $postId;
        $testPostModel->title   = $title;
        $testPostModel->content = $content;

        return $testPostModel;
    }
}
