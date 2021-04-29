<?php

namespace App\Traits;

trait ApiResponser{

    protected function successResponse($data, $message = "success", $code = 200)
    {
        return response()->json([
            'status'=> 'success',
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = '', $code)
    {
        return response()->json([
            'status'=>'error',
            'message' => $message,
            'code' => $code,
            'data' => null
        ], $code);
    }

}