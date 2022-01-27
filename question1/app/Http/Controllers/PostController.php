<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getPostById($postId)
    {
        $validator = Validator::make(['postId' => $postId], [
            'postId' => 'integer|exists:App\Models\Post,id',
        ]);
        $this->handleDefaultValidatorException($validator);

        $post = $this->postService->getPostById($postId);

        return $this->successFormat($post);
    }
}
