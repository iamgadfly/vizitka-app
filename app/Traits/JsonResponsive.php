<?php

namespace App\Traits;

trait JsonResponsive
{
    protected function success(mixed $data, int $code = 200, string $message = null): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => 'Success'
        ];

        if (!is_null($data)) $response['data'] = $data;
        if (!is_null($message)) $response['message'] = $message;

        return response()->json($response, $code);
    }

    protected function error(string $message, int $code): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
        ], $code);
    }
}
