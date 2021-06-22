<?php

namespace App\Http\Controllers\Api;

use App\Earning;
use App\Helpers\Code;
use App\Helpers\Operator;
use App\Helpers\User;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Point;
use App\UserApp;
use App\UserCash;
use App\UserConfig;
use App\UserTargetInfo;
use App\UserTime;
use Aws\RAM\Exception\RAMException;
use AWSHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use OpenApi\Util;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;

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
//                    'opname' => strval(Operator::getNameByOpcode($userTargetInfo->opcode)),
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

            User::purge_cache($user->uid);

            return $this->successResponse($data);
        } else {
            $data = [
                'code' => 401,
                'status' => false,
                'message' => 'Email not found.'
            ];
            return $this->successResponse($data);
//            return $this->errorResponse($validation->errors(),static::ERROR_USER_NOT_FOUND);
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
        $user_by_account_id = UserApp::where('account_id', '=', $request->id)->first();

        if ($user) {
            if (is_null($user->account_id)) { //&& !$user_by_account_id
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
            } else {
                if ($user->email != $request->email) {
                    $data = [
                        'code' => 401,
                        'status' => false,
                        'message' => 'Phone number and email does not match.'
                    ];
                    return $this->successResponse($data);
//                    return $this->errorResponse($validation->errors(),static::PHONE_EMAIL_NOT_SYNC);
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

            User::purge_cache($user->uid);

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
            $inv_code = strval(Utils::generateInvCode());
            DB::beginTransaction();
            try {
                if (strlen($request->profile_img) > 1) {
                    $profile_img = $request->profile_img;
                } else {
                    $profile_img = null;
                }

                $createUser = UserApp::create([
                    'uid' => (int) $uid,
                    'sim' => $request->id,
                    'anid' => $request->anid,
                    'imei' => $request->imei,
                    'gaid' => $request->gaid,
                    'inv_code' => $inv_code,
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
                    'is_rooted' => $request->rt == 't' ? 't' : 'f'
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
                    'ses' => $ses,
                    'appopen' => date("Y-m-d H:i:s"),
                    'last_ip' => ip2long($request->getClientIp()),
                    'last_ad_list' => null,
                ]);

                $createUserTargetInfo = UserTargetInfo::insert([
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

            $updateUserTime = UserTime::where('uid', $uid)->update([
                'ses' => $ses,
                'appopen' => date("Y-m-d H:i:s"),
                'last_ip' => ip2long($request->getClientIp()),
            ]);

            $updateUserTargetInfo = UserTargetInfo::where('uid', $uid)->update([
                'locale' => 'id',
                'opcode' => $request->opcode,
                'osver' => $request->ov,
                'appver' => $request->av,
                'resw' => $request->resw,
                'resh' => $request->resh,
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]);

        } else {
            $data = [
                'code' => 401,
                'status' => false,
                'message' => 'Email already registered.'
            ];
            return $this->successResponse($data);
//            return $this->errorResponse($validation->errors(),static::USER_EMAIL_EXIST);
        }

        //Prepare login Here
        $data = [
            'session' => [
                'u'             => strval($uid),
                's'             => strval($request->id),
                'ses'           => strval($ses),
                'registered'    => true,
            ],
            'info' => [
                'u' => intval($uid),
                'id' => strval($uid),
                'inv_code' => $inv_code,
                'reg_tm' => date("Y-m-d H:i:s"),
                'ph' => null,
                'lock_screen' => false,
                'allow_noti' => true,
                'invite_url' => 'http://inv.sctrk.site/',
//                'opname' => strval(Operator::getNameByOpcode(strval($createUserTargetInfo->opcode))),
                'opname' => 'Indosat',
                'opcode' => $request->opcode,
                'gender' => null,
                'birth' => null,
                'email' => $request->email,
                'full_name' => $request->display_name,
                'first_name' => $request->give_name,
                'last_name' => $request->family_name,
                'profile_img' => $request->profile_img
            ],
            'cash_status' => [
                'total' => 0,
                'earn_today' => 0,
                'last_transaction' => 0
            ]
        ];

        User::purge_cache($uid);

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

    /**
     * @OA\Get(
     *   path="/api/point-history",
     *   summary="list of point",
     *   tags={"point history"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="A list of notifications"
     *   )
     * )
     */
    public function point_history(Request $request){
        $user = $this->user;
        $uid = $user->uid;
        $point = Earning::on(Utils::db_earning($user->uid))
        ->where('uid' , $uid)
        ->where('tm' , '>' , date('Y-m-d', strtotime('today - 30 days')))
       ->orderBy('seq' , 'DESC')
        ->limit(50)->get();
        $data = [];
        foreach($point as $item){
            $item['title'] = $item->detail;
            $date = date('Y-m-d' , strtotime($item->tm));
            if($item->detail == '' || $item->detail == null){
                $item['title'] = trans('code.'.Code::getLang($item->code));
            }
            $data[$date][] = $item;
        }
        $d=[];
        foreach($data as $k=>$v){
            $d[] = array(
                'date' => $k,
                'detail' => $data[$k]
            );

        }
         return $this->successResponse($d);
    }

    public function voucher_history(){
        $user = $this->user;
        $uid = $user->uid;
        $point = Point::select('user_earning_detail.txt as title','user_earning_data.*')
            ->leftJoin('user_earning_detail' , 'user_earning_data.detail' ,'user_earning_detail.id')
            ->where('user_earning_data.uid' , $uid)
            ->where('user_earning_data.tm' , '>' , date('Y-m-d', strtotime('today - 30 days')))
            ->orderBy('user_earning_data.seq' , 'DESC')
            ->limit(50)->get();
        if(!$point){
            return $this->successResponse([]);
        }
        $data = [];
        foreach($point as $item){
            $date = date('Y-m-d' , strtotime($item->tm));
            if($item->title == '' || $item->title == null){
                $item['title'] = 'Kamu dapat Poin';
            }
            $data[$date][] = $item;
        }
        $d=[];
        foreach($data as $k=>$v){
            $d[] = array(
                'date' => $k,
                'detail' => $data[$k]
            );

        }
        return $this->successResponse($d);

    }
    /**
     * @OA\Get(
     *   path="/api/invite-history",
     *   summary="list of point",
     *   tags={"point history"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="A list of notifications"
     *   )
     * )
     */
    public function invite(Request $request){
        $response = Http::post('https://api.ctree.id/api2/user/cash/all.json', [
            'mmses' => $request->mmses,
        ]);
        $datas = [];
        $data = Utils::result_http_request($response->body() , 'list');
        if(count($data) > 0){
            foreach($data as $v){
                $date = trim($v['t']);
                if($v['tt'] == '' || $v['tt'] == null){
                    $v['title'] = 'Kamu dapat Poin';
                }
                $datas[$date][] = $v;
            }
            $d=[];
            foreach($datas as $k=>$v){
                $d[] = array(
                    'date' => $k,
                    'detail' => $datas[$k]
                );

            }
            return $this->successResponse($d);
        }
    }

}
