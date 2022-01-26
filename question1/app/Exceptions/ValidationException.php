<?php

namespace App\Exceptions;

class ValidationException extends BaseException
{
    public const TYPE = 'VALIDATION_ERROR';

    public const ERROR_FIELD = 'B00001';
    public const ERROR_DATA_NOT_EXISTS = 'B00002';
    public const ERROR_DATA_FORMAT = 'B00003';

    // Validation Error Codes
    public const ERRORS = [
        'B00001' => [422, 'Field Error'],
        'B00002' => [422, 'Data Not Exists'],
        'B00003' => [422, 'Data Format Error'],
    ];

    public function __construct($errorCode, $field = null, $reason = null)
    {
        $this->errorCode = $errorCode;

        if (!is_null($field)) {
            $this->extraInfo['field'] = $field;
        }
        if (!is_null($reason)) {
            $this->extraInfo['reason'] = $reason;
        }
    }
}
