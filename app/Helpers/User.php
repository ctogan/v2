<?php
namespace App\Helpers;

use App\Earning;
use App\UserApp;
use App\UserCash;
use App\UserTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Self_;

class User {

    const STATUS_NORMAL = '0';
    const STATUS_SLEEP = 'S';
    const STATUS_BLOCK = 'B';
    const STATUS_BAN = 'b';

    const ABUSE_NONE = '0';
    const ABUSE_CANT_USE = '1';
    const ABUSE_CANT_EARN_RIGHT = '2';
    const ABUSE_CANT_EARN = '3';
    const ABUSE_AD_LIMIT = '4';

    const PHONE_AUTH_TYPE_SUCCESS_SELF_SMS = '0';
    const PHONE_AUTH_TYPE_FAIL_LEGACY = '1';
    const PHONE_AUTH_TYPE_SUCCESS_SMS = '2';
    const PHONE_AUTH_TYPE_ADMIN = '3';
    const PHONE_AUTH_TYPE_DEL = '9';

    const ERR_SESSION = "Session Error";
    const USER_NOT_FOUND = "User Not Found";
    const USER_SESSION_NO_MMSES = "Session Key Error";
    const USER_SESSION_SES_NOT_MATCH = "Ses Not Match";

    const CPR = 'cpr';

    public static $strictMode = false;

    public static $statusLabel = [
        User::STATUS_NORMAL => 'Normal',
        User::STATUS_SLEEP => 'Sleep',
        User::STATUS_BLOCK => 'Blocked',
        User::STATUS_BLOCK.'3' => 'Blocked (3 days)',
        User::STATUS_BLOCK.'7' => 'Blocked (7 days)',
        User::STATUS_BAN => 'Banned',
    ];

    public static $abuseLabel = [
        User::ABUSE_NONE => 'None',
        User::ABUSE_AD_LIMIT => 'Cannot see CPI/CPE',
        User::ABUSE_CANT_USE => 'Cannot use Cash',
        User::ABUSE_CANT_EARN_RIGHT => 'Cannot use Cash, right slide reward',
        User::ABUSE_CANT_EARN => 'Cannot use and earn Cash',
    ];

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

    public static function earn_point($user, $code, $cash, $detail=null, $ad=null, $push_delay =0, $uniq = null ){
        $today = date('Y-m-d');

        $user_cash = UserCash::where("uid",'=',$user->uid)->first();
        if (!self::can_earn_cash($user)) {
            return false;
        }

        $cash = round(abs($cash));
        if ($cash <= 0) {
            return false;
        }

        if (!$uniq && $ad && $ad->type != Code::TYPE_CPR) {
            $uniq = $ad->adid;
        }

        $earning = [
            'uid' => $user->uid,
            'code' => $code,
            'cash' => $cash,
            'before_free_cash' => 0,
            'before_work_cash' => $user_cash->cash,
            'after_free_cash' => 0,
            'after_work_cash' => $user_cash->cash + $cash,
            'detail' => $detail,
            'uniq' => $uniq
        ];

        if (!Earning::insert($earning)) {
            return false;
        }

        $user_cash->total_earn += $cash;
        if ($today != $user_cash->last_earn) {
            $user_cash->today_earn = 0;
            if (substr($today, 0, 7) != substr($user_cash->last_earn, 0, 7)) {
                $user_cash->month_earn = 0;
            }
            $user_cash->last_earn = $today;
        }

        if (!$user_cash->save()){
            return false;
        }

        Push::notification($user->token, 'Cashtree', 'Got Reward +' .$cash , '','');

        return true;
    }

    public static function can_earn_cash($user) {
        if ($user->status != User::STATUS_NORMAL || $user->abuse == User::ABUSE_CANT_EARN) {
            return false;
        }
        return true;
    }

    public static function use_cash($user, $code, $cash, $detail = null, $uniq = null, $force = false){
        $user_cash = UserCash::where("uid",'=',$user->uid)->first();
        if (!self::can_earn_cash($user)) {
            return false;
        }

        $today = date('Y-m-d');

        if (!self::can_earn_cash($user)) {
            return false;
        }

        $cash = abs($cash);
        $last_cash = $user->total_earn - $user->total_use;
        if (!$force && $user_cash < $cash) {
            //throw Err::INTERNAL(gt('Cash is not enough'));
            return false;
        }

        $earning = [
            'uid' => $user->uid,
            'code' => $code,
            'cash' => $cash,
            'before_free_cash' => 0,
            'before_work_cash' => $last_cash,
            'after_free_cash' => 0,
            'after_work_cash' => $last_cash + $cash,
            'detail' => $detail,
            'uniq' => $uniq
        ];

        if (!Earning::insert($earning)) {
            return false;
        }

        $user_cash->total_use += $cash;
        if ($today != $user_cash->last_earn) {
            $user_cash->today_earn = 0;
            if (substr($today, 0, 7) != substr($user_cash->last_earn, 0, 7)) {
                $user_cash->month_earn = 0;
            }
            $user_cash->last_earn = $today;
        }

        if (!$user_cash->save()){
            return false;
        }

        return true;
    }
}