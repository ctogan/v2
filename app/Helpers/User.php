<?php
namespace App\Helpers;

use App\UserApp;
use App\UserTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class User {

    protected const ERR_SESSION = "Session Error";
    protected const USER_NOT_FOUND = "User Not Found";
    protected const USER_SESSION_NO_MMSES = "Session Key Error";
    protected const USER_SESSION_SES_NOT_MATCH = "Ses Not Match";

    public static function session(Request $request) {
        $validation = Validator::make($request->all(), [
            'mmses' => 'required'
        ]);

        if($validation->fails()) {
            return static::session_error(static::ERR_SESSION);
        }

        $mm = Utils::decrypt(preg_replace('/ /', '+', $request->mmses));

        if (!$mm) {
            return static::session_error(static::USER_SESSION_NO_MMSES);
        }

        list($ses, $sim, $tm) = explode('^', $mm);

        $user = DB::connection("users")->table("view_user_2")->where("sim",'=',$sim)->first();
        if (!$user || !$user->uid) {
            return static::session_error(static::USER_NOT_FOUND);
        }

        if ($user->ses != $ses) {
            return static::session_error(static::USER_SESSION_SES_NOT_MATCH);
        }

        return static::session_success($user);
    }

    public static function session_error($message){
        return ['status'=> false, 'message'=> $message];
    }

    public static function session_success($user){
        return ['status'=> true, 'data'=> $user];
    }
}