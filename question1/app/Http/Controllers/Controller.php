<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successFormat($data = [])
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function failFormat($error, $httpCode = 500)
    {
        return response()->json([
            'status' => 'error',
            'error' => $error
        ], $httpCode);
    }

    public function handleDefaultValidatorException($validator)
    {
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->getMessages();
            $firstErrorField = array_key_first($errorMessages);
            throw new ValidationException(ValidationException::ERROR_FIELD, $firstErrorField, $errorMessages[$firstErrorField][0]);
        }
    }
}
