<?php

namespace App\Exceptions;

class UndefinedException extends BaseException
{
    public const TYPE = 'UNDEFINED_ERROR';

    public function getErrorCode()
    {
        return 'U00001';
    }

    public function getErrorMessage()
    {
        return $this->getMessage();
    }

    public function getHttpStatusCode()
    {
        return 500;
    }
}
