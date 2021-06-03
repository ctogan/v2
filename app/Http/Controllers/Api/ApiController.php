<?php

namespace App\Http\Controllers\Api;

use App\Helpers\User;
use App\Http\Controllers\Controller;
use App\Option;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\UserBlock;

class ApiController extends Controller
{
    use ApiResponser;

    protected $user;

    const IGNORE_PATH = array(
        'api/user/auth/check-phone-number',
        'api/user/auth/verify-otp',
        'api/user/auth/request-otp',
        'api/user/auth/login/email',
        'api/user/auth/login/phone',
        'api/user/auth/register'
    );

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
//            $maintenance = Option::where('option_key' ,'maintenance_mode')->where('option_value' , 'on')->first();
//            if($maintenance){
//                return $this->errorResponse('UNDER MAINTENANCE',static::ERROR_MAINTENANCE);
//            }

            if(in_array($request->path(), self::IGNORE_PATH)){
                return $next($request);
            }

            if($request->mmses){
                $user = User::session($request);
                if(!$user['status']){
                    return $this->errorResponse($user['message'],static::ERROR_USER_AUTH);
                }
                $this->user = $user['data'];
            }else{
                return $this->errorResponse(static::TRANSACTION_ERROR_USER_NEED_LOGIN,static::ERROR_USER_AUTH);
            }

            return $next($request);
        });
    }

}
