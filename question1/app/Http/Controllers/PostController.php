<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Exception;

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

    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $this->handleDefaultValidatorException($validator);

        DB::beginTransaction();
        try {
            $this->postService->createPost($request->title, $request->content);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $this->successFormat();
    }
}
