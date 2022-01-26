<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

use Exception;

abstract class BaseException extends Exception
{
    public const TYPE = 'BASE_ERROR';
    public const ERRORS = [];

    protected $errorCode;
    protected $extraInfo = [];

    public function render($request)
    {
        return response()->json($this->genErrorResponseData(), $this->getHttpStatusCode());
    }

    public function report()
    {
        Log::error(
            json_encode($this->genErrorResponseData()),
            ['exception' => $this]
        );
    }

    private function genErrorResponseData()
    {
        $error = [
            'type' => static::TYPE,
            'message' => $this->getErrorMessage(),
            'code' => $this->getErrorCode(),
        ];

        if (!empty($this->extraInfo)) {
            $error = array_merge($error, $this->extraInfo);
        }

        $responseData = [
            'status' => 'error',
            'error' => $error,
        ];

        return $responseData;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        return static::ERRORS[$this->getErrorCode()][1];
    }

    public function getHttpStatusCode()
    {
        return static::ERRORS[$this->getErrorCode()][0];
    }
}
