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
}
