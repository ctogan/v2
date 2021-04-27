<?php

namespace App\Traits;

trait ApiResponser{

    protected function successResponse($data, $message = "success", $code = 200)
    {
        return response()->json([
            'status'=> 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = '', $code)
    {
        return response()->json([
            'status'=>'error',
            'message' => $message,
            'data' => null
        ], $code);
    }

}