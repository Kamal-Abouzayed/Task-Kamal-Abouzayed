<?php

namespace App\Traits;

trait ApiResponse
{
    public function apiResponse($message, $data = [], $status = 200)
    {
        return response([
            'success' => in_array($status ,$this->successCode())? true : false,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    public function successCode()
    {

        return [
            200 ,201 ,202
        ];

    } // end of success code
}
