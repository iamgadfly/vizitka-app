<?php

namespace App\Traits;

trait JsonResponsive
{
    protected function success(mixed $data, string $message = null, int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error(string $message, int $code, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
