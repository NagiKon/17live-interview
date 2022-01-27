<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
}
