<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
}
