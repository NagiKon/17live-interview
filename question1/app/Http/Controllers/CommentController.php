<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Exception;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function getCommentById($postId, $commentId)
    {
        $validator = Validator::make([
            'postId'    => $postId,
            'commentId' => $commentId
        ], [
            'postId'    => 'integer|exists:App\Models\Post,id',
            'commentId' => 'integer|exists:App\Models\Comment,id',
        ]);
        $this->handleDefaultValidatorException($validator);

        $post = $this->commentService->getCommentById($postId, $commentId);

        return $this->successFormat($post);
    }

    public function createComment(Request $request, $postId)
    {
        $message = $request->message;
        $validator = Validator::make([
            'postId'  => $postId,
            'message' => $message
        ], [
            'postId'  => 'integer|exists:App\Models\Post,id',
            'message' => 'required|string',
        ]);
        $this->handleDefaultValidatorException($validator);

        DB::beginTransaction();
        try {
            $this->commentService->createComment($postId, $message);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $this->successFormat();
    }

    public function updateComment(Request $request, $postId, $commentId)
    {
        $message = $request->message;
        $validator = Validator::make([
            'postId'    => $postId,
            'commentId' => $commentId,
            'message'   => $message,
        ], [
            'postId'    => 'integer|exists:App\Models\Post,id',
            'commentId' => 'integer|exists:App\Models\Comment,id',
            'message'   => 'required|string',
        ]);
        $this->handleDefaultValidatorException($validator);

        DB::beginTransaction();
        try {
            $result = $this->commentService->updateComment($postId, $commentId, $message);
            DB::commit();

            return $this->successFormat($result);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteCommentById($postId, $commentId)
    {
        $validator = Validator::make([
            'postId'    => $postId,
            'commentId' => $commentId
        ], [
            'postId'    => 'integer|exists:App\Models\Post,id',
            'commentId' => 'integer|exists:App\Models\Comment,id',
        ]);
        $this->handleDefaultValidatorException($validator);

        DB::beginTransaction();
        try {
            $this->commentService->deleteCommentById($postId, $commentId);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $this->successFormat();
    }
}
