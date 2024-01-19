<?php

namespace App\Traits;

trait ApiResponser
{
    protected function token($personalAccessToken, $message = null, $code = 200)
    {
        $tokenData = [
            'access_token' => $personalAccessToken,
            'token_type' => 'Bearer',
        ];

        return $this->successResponse($tokenData, $message, $code);
    }

    protected function successResponse($data = null, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
            'status_code' => $code,
        ], $code);
    }

    protected function errorResponse($code, $message = null)
    {
        return response()->json([
            'status' => 'Error',
            // 'method'    => request()->route()->getActionMethod(),
            'message' => $message,
            'status_code' => $code,
        ], $code);
    }
}
