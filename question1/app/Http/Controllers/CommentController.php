<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Exception;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
}
