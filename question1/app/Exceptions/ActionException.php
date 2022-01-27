<?php

namespace App\Exceptions;

class ActionException extends BaseException
{
    public const TYPE = 'ERROR_ACTION';

    public const ERROR_CONTENT_TYPE_JSON_HEADER = 'C00001';
    public const ERROR_CONTENT_TYPE_JSON_BODY   = 'C00002';

    public const ERROR_POST_NOT_EXISTS          = 'C01001';
    // Action Error Codes
    public const ERRORS = [
        'C00001' => [400, 'The content-type of header must be application/json.'],
        'C00002' => [400, 'The content-type of body must be a json.'],
        'C01001' => [404, 'This post is not exist.'],
    ];

    public function __construct($errorCode, $detail = null)
    {
        $this->errorCode = $errorCode;
        if (!is_null($detail)) {
            $this->extraInfo['detail'] = $detail;
        }
    }
}
