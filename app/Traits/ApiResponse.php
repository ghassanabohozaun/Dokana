<?php
namespace App\Traits;

trait ApiResponse
{
    // response
    protected function ApiResponse($data, $status, $msg, $code)
    {
        $repsonse = [
            'status' => $status,
            'message' => $msg,
            'data' => $data,
        ];

        return response()->json($repsonse, $code);
    }
}
