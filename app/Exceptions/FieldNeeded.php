<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class FieldNeeded extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => "Field '{$this->getMessage()}' Not Provided"
        ], 400);
    }
}
