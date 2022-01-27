<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use App\Services\PostService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Exception;

class PostController extends Controller
{
    private $commentService;
    private $postService;

    public function __construct(CommentService $commentService, PostService $postService)
    {
        $this->commentService = $commentService;
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

    public function updateEntirePostById(Request $request, $postId)
    {
        $parameter = array_merge(['postId' => $postId], $request->all());
        $validator = Validator::make($parameter, [
            'postId' => 'integer|exists:App\Models\Post,id',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $this->handleDefaultValidatorException($validator);

        DB::beginTransaction();
        try {
            $result = $this->postService->updateEntirePostById($postId, $request->title, $request->content);
            DB::commit();

            return $this->successFormat($result);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updatePostById(Request $request, $postId)
    {
        $parameter = array_merge(['postId' => $postId], $request->all());
        $validator = Validator::make($parameter, [
            'postId' => 'integer|exists:App\Models\Post,id',
            'title' => 'string|nullable',
            'content' => 'string|nullable',
        ]);
        $this->handleDefaultValidatorException($validator);

        DB::beginTransaction();
        try {
            $result = $this->postService->updatePostById($postId, $request->title, $request->content);
            DB::commit();

            return $this->successFormat($result);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deletePostById($postId)
    {
        $validator = Validator::make(['postId' => $postId], [
            'postId' => 'integer|exists:App\Models\Post,id',
        ]);
        $this->handleDefaultValidatorException($validator);

        DB::beginTransaction();
        try {
            $this->postService->deletePostById($postId);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $this->successFormat();
    }

    public function getCommentListByPostId(Request $request, $postId)
    {
        $parameter = array_merge(['postId' => $postId], $request->all());
        $validator = Validator::make($parameter, [
            'postId'  => 'integer|exists:App\Models\Post,id',
            'page'    => 'integer|nullable|min:1',
            'perPage' => 'integer|nullable|min:1',
        ]);
        $this->handleDefaultValidatorException($validator);

        $page        = intval($request->input('page', 1));
        $perPage     = intval($request->input('perPage', 6));
        $commentList = $this->commentService->getCommentListByPostId($postId, $page, $perPage);

        return $this->successFormat($commentList);
    }
}
