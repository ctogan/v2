<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\UserApp;
use Aws\RAM\Exception\RAMException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * @OA\Post(
     *   path="/api/user/auth/login",
     *   summary="login",
     *   tags={"auth"},
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="user is exist = true | false"
     *   )
     * )
     */
    public function login(Request $request){

    }

    /**
     * @OA\Post(
     *   path="/api/user/auth/register",
     *   summary="register",
     *   tags={"auth"},
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="user is exist = true | false"
     *   )
     * )
     */
    public function register(Request $request){

    }

    /**
     * @OA\Post(
     *   path="/api/user/auth/check-phone-number",
     *   summary="check the user whether it exists or not",
     *   tags={"auth"},
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="user is exist = true | false"
     *   )
     * )
     */
    public function check_phone_number(Request $request){
        $validation = Validator::make($request->all(), [
            'phone_number' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $user = UserApp::where('phone','=',$request->phone_number)->first();
        $exist = false;
        $need_otp = true;
        if($user){
            $exist = true;

            if($user->email != null){
                $need_otp = false;
            }
        }

        $data = [
            'auth' => [
                'exist' => $exist,
                'need_otp' => $need_otp
            ]
        ];

        return $this->successResponse($data);
    }

    /**
     * @OA\Post(
     *   path="/api/user/auth/verify-otp",
     *   summary="check the user number and otp",
     *   tags={"auth"},
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="otp",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="user is verified = true | false"
     *   ),
     *   @OA\Response(
     *     response=219,
     *     description="user not found"
     *   ),
     *   @OA\Response(
     *     response=220,
     *     description="invalid OTP"
     *   )
     * )
     */
    public function verify_otp(Request $request){
        $validation = Validator::make($request->all(), [
            'phone_number' => 'required',
            'otp' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $user = UserApp::where('phone','=',$request->phone_number)->first();

        if(!$user){
            return $this->errorResponse(static::ERROR_USER_NOT_FOUND,static::ERROR_CODE_USER_NOT_FOUND);
        }

        if($user->otp != $request->otp){
            return $this->errorResponse(static::ERROR_USER_OTP,static::ERROR_CODE_USER_OTP);
        }

        return $this->successResponse(true);
    }

    /**
     * @OA\Post(
     *   path="/api/user/auth/request-otp",
     *   summary="generate a new otp for phone number",
     *   tags={"auth"},
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Generated OTP"
     *   ),
     *   @OA\Response(
     *     response=219,
     *     description="user not found"
     *   )
     * )
     */
    public function request_otp(Request $request){
        $validation = Validator::make($request->all(), [
            'phone_number' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $user = UserApp::where('phone','=',$request->phone_number)->first();

        if(!$user){
            return $this->errorResponse(static::ERROR_USER_NOT_FOUND,static::ERROR_CODE_USER_NOT_FOUND);
        }
        $otp = rand(1000,9000);

        //todo update otp

        //todo send sms

        $data = [
            'auth' => [
                'exist' => true,
                'otp' =>$otp
            ]
        ];

        return $this->successResponse($data);
    }
}