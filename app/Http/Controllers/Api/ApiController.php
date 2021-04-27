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

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
//            $maintenance = Option::where('option_key' ,'maintenance_mode')->where('option_value' , 'on')->first();
//            if($maintenance){
//                return $this->errorResponse('UNDER MAINTENANCE',static::ERROR_MAINTENANCE);
//            }
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
