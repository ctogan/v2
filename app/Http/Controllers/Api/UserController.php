<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Operator;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\UserApp;
use App\UserCash;
use App\UserConfig;
use App\UserTargetInfo;
use App\UserTime;
use Aws\RAM\Exception\RAMException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * @OA\Post(
     *   path="/api/user/auth/login/email",
     *   summary="login to app using gmail",
     *   tags={"auth"},
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="email",
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
    public function login_email(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'email' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $user = UserApp::where('account_id', $request->id)->first();

        //Prepare login here
        if ($user) {
            $userConfig = UserConfig::where('uid', $user->uid)->first();
            $userCash = UserCash::where('uid', $user->uid)->first();
            $userTime = UserTime::where('uid', $user->uid)->first();
            $userTargetInfo = UserTargetInfo::where('uid', $user->uid)->first();
            $ses = substr(md5(microtime()), 0, 20);

            $updateUser = UserApp::where('uid', $user->uid)->update([
                'gaid' => $request->gaid,
                'imei' => $request->imei,
                'anid' => $request->anid,
            ]);
            $updateUserTargetInfo = UserTargetInfo::where('uid', $user->uid)->update([
                'locale' => $request->lc,
                'device_name' => $request->dvc,
                'opcode' => $request->opcode,
                'osver' => $request->ov,
                'appver' => $request->av,
                'resw' => $request->resw,
                'resh' => $request->resh,
                'lat' => $request->lat,
                'lng' => $request->lng
            ]);
            $updateUserTime = UserTime::where('uid', $user->uid)->update([
                'ses' => $ses,
                'last_ip' => ip2long($request->getClientIp())
            ]);

            $data = [
                'session' => [
                    'u'             => strval($user->uid),
                    's'             => strval($user->sim),
                    'ses'           => strval($ses),
                    'registered'    => true,
                ],
                'info' => [
                    'u' => intval($user->uid),
                    'id' => strval($user->uid),
                    'inv_code' => strval($user->inv_code),
                    'reg_tm' => strtotime($userTime->register),
                    'ph' => strval($user->phone),
                    'lock_screen' => boolval($userConfig->lock_screen),
                    'allow_noti' => boolval($userConfig->allow_noti),
                    'invite_url' => 'http://inv.sctrk.site/',
                    'opname' => strval(Operator::getNameByOpcode($userTargetInfo->opcode)),
                    'opcode' => strval($userTargetInfo->opcode),
                    'gender' => $userTargetInfo->gender ? $userTargetInfo->gender : 'U',
                    'birth' => strval($userTargetInfo->birth),
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'profile_img' => $user->profile_img
                ],
                'cash_status' => [
                    'total' => intval($userCash->cash),
                    'earn_today' => intval($userCash->today_earn),
                    'last_transaction' => strval($userCash->last_earn)
                ]
            ];

//        Abuse::LogoutOtherUsersInSameMachine($user);

            return $this->successResponse($data);
        } else {
            return $this->errorResponse($validation->errors(),static::ERROR_USER_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/user/auth/login/phone",
     *   summary="login to application using phone number",
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
     *          name="id",
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
    public function login_phone(Request $request){
        $validation = Validator::make($request->all(), [
            'enc' => 'required',
            'ov' => 'required',
            'opcode' => 'required',
            'av' => 'required',
            'lc' => 'required',
            'phone_number' => 'required',
            'id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        //Login Logic Here
        $user = UserApp::where('phone', '=', $request->phone_number)->first();

        if ($user) {
            if (is_null($user->account_id)) {
                $connectEmail = UserApp::where('uid', $user->uid)->update([
                    'first_name' => $request->give_name,
                    'last_name' => $request->family_name,
                    'full_name' => $request->display_name,
                    'email' => $request->email,
                    'account_id' => $request->id
                ]);

                if ($connectEmail <= 0) {
                    return $this->errorResponse($validation->errors(),static::TRANSACTION_ERROR_GENERAL);
                }
            }

            $userConfig = UserConfig::where('uid', $user->uid)->first();
            $userCash = UserCash::where('uid', $user->uid)->first();
            $userTime = UserTime::where('uid', $user->uid)->first();
            $userTargetInfo = UserTargetInfo::where('uid', $user->uid)->first();

            $ses = substr(md5(microtime()), 0, 20);

            $user->gaid = $request->gaid;
            $user->imei = $request->imei;
            $user->anid = $request->anid;
            $user->save();

            $userTargetInfo->locale = $request->lc;
            $userTargetInfo->device_name = $request->dvc;
            $userTargetInfo->opcode = $request->opcode;
            $userTargetInfo->osver = $request->ov;
            $userTargetInfo->appver = $request->resw;
            $userTargetInfo->resw = $request->resh;
            $userTargetInfo->resh = $request->lat;
            $userTargetInfo->lat = $request->lng;
            $userTargetInfo->save();

            $userTime->ses = $ses;
            $userTime->last_ip = ip2long($request->getClientIp());
            $userTime->save();

            $data = [
                'session' => [
                    'u'             => strval($user->uid),
                    's'             => strval($user->sim),
                    'ses'           => strval($ses),
                    'registered'    => true,
                ],
                'info' => [
                    'u' => intval($user->uid),
                    'id' => strval($user->uid),
                    'inv_code' => strval($user->inv_code),
                    'reg_tm' => strtotime($userTime->register),
                    'ph' => strval($user->phone),
                    'lock_screen' => boolval($userConfig->lock_screen),
                    'allow_noti' => boolval($userConfig->allow_noti),
                    'invite_url' => 'http://inv.sctrk.site/',
//                    'opname' => Operator::getNameByOpcode($userTargetInfo->opcode),
                    'opname' => 'Indosat',
                    'opcode' => strval($userTargetInfo->opcode),
                    'gender' => $userTargetInfo->gender ? $userTargetInfo->gender : 'U',
                    'birth' => strval($userTargetInfo->birth),
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'profile_img' => $user->profile_img
                ],
                'cash_status' => [
                    'total' => intval($userCash->cash),
                    'earn_today' => intval($userCash->today_earn),
                    'last_transaction' => strval($userCash->last_earn)
                ]
            ];

            return $this->successResponse($data);
        } else {
            return $this->errorResponse($validation->errors(),static::ERROR_USER_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/user/auth/register",
     *   summary="register",
     *   tags={"auth"},
     *     @OA\Parameter(
     *          name="anid",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="imei",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="gaid",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="give_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="family_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="display_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="opcode",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="ov",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="av",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="resw",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="resh",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="lat",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="lng",
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
        $validation = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $user = UserApp::where('email', '=', $request->email)->first();
        $ses = substr(md5(microtime()), 0, 20);

        if (!$user) {
            //Register logic
            $query = "nextval('uid') as uid";
            $uid = UserApp::selectRaw($query)->value('uid');
            DB::beginTransaction();
            try {
                if (count($request->profile_img) > 1) {
                    $profile_img = $request->profile_img;
                } else {
                    $profile_img = null;
                }

                $createUser = UserApp::create([
                    'uid' => (int) $uid,
                    'sim' => $request->anid,
                    'anid' => $request->anid,
                    'imei' => $request->imei,
                    'gaid' => $request->gaid,
                    'first_name' => $request->give_name,
                    'last_name' => $request->family_name,
                    'full_name' => $request->display_name,
                    'email' => $request->email,
                    'account_id' => $request->id,
                    'profile_img' => $profile_img
                ]);

                $createUserConfig = UserConfig::create([
                    'uid' => (int) $uid,
                    'auto_buying' => false,
                    'lock_screen' => false,
                    'allow_noti' => true,
                    'status' => '0',
                    'abuse' => '0',
                    'sel_goods_id' => null,
                    'is_rooted' => false
                ]);

                $createUserCash = UserCash::create([
                    'uid' => (int) $uid,
                    'total_earn' => 0,
                    'total_use' => 0,
                    'last_earn' => null,
                    'month_earn' => 0,
                    'total_pulsa' => 0,
                    'parent_uid' => null,
                    'inv_count' => 0,
                    'inv_cash_total' => 0
                ]);

                $createUserTime = UserTime::create([
                    'uid' => (int) $uid,
                    'register' => date("Y-m-d H:i:s"),
                    'changed' => date("Y-m-d H:i:s"),
                    'login' => date("Y-m-d H:i:s"),
                    'last_ad_list' => null,
                    'appopen' => date("Y-m-d H:i:s"),
                    'ses' => $ses,
                    'last_ip' => ip2long($request->getClientIp())
                ]);

                $createUserTargetInfo = UserTargetInfo::create([
                    'uid' => (int) $uid,
                    'tm_target_changed' => date("Y-m-d H:i:s"),
                    'locale' => 'id',
                    'opcode' => $request->opcode,
                    'osver' => $request->ov,
                    'appver' => $request->av,
                    'resw' => $request->resw,
                    'resh' => $request->resh,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'gender' => null,
                    'birth' => null,
                    'marriage' => null,
                    'religion' => null,
                    'device_name' => null,
                ]);

                DB::commit();
            }catch (\Exception $e) {
                DB::rollback();
            }

        } else {
            return $this->errorResponse($validation->errors(),static::USER_EMAIL_EXIST);
        }

        //Prepare login Here
        $data = [
            'session' => [
                'u'             => strval($createUser->uid),
                's'             => strval($createUser->sim),
                'ses'           => strval($ses),
                'registered'    => true,
            ],
            'info' => [
                'u' => intval($createUser->uid),
                'id' => strval($createUser->uid),
                'inv_code' => strval($createUser->inv_code),
                'reg_tm' => strtotime($createUserTime->register),
                'ph' => strval($createUser->phone),
                'lock_screen' => boolval($createUserConfig->lock_screen),
                'allow_noti' => boolval($createUserConfig->allow_noti),
                'invite_url' => 'http://inv.sctrk.site/',
//                'opname' => strval(Operator::getNameByOpcode(strval($createUserTargetInfo->opcode))),
                'opname' => 'Indosat',
                'opcode' => strval($createUserTargetInfo->opcode),
                'gender' => $createUserTargetInfo->gender ? $createUserTargetInfo->gender : 'U',
                'birth' => strval($createUserTargetInfo->birth),
                'email' => $createUser->email,
                'full_name' => $createUser->full_name,
                'first_name' => $createUser->first_name,
                'last_name' => $createUser->last_name,
                'profile_img' => $createUser->profile_img
            ],
            'cash_status' => [
                'total' => intval($createUserCash->cash),
                'earn_today' => intval($createUserCash->today_earn),
                'last_transaction' => strval($createUserCash->last_earn)
            ]
        ];

        return $this->successResponse($data);
    }

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

        if(trim($user->otp) != trim($request->otp)){
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
     *     description="Generated OTP, OTP status, if need_otp=false means no need OTP"
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

        $exist = false;
        $need_otp = true;
        $otp = null;
        if($user){
            $exist = true;

            if($user->email != null){
                $need_otp = false;
            }else{
//                $otp = rand(1000,9000);
                $otp = 1234;
                $user->otp = $otp;
                $user->save();

                //todo send sms
            }
        }

        $data = [
            'auth' => [
                'exist' => $exist,
                'need_otp' => $need_otp,
                'otp' =>$otp
            ]
        ];

        return $this->successResponse($data);
    }
}