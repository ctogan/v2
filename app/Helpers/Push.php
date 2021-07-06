<?php
namespace App\Helpers;

use App\Notification;
use App\NotificationDetail;
use App\UserToken;

class Push {

    const ERR_PUSH_NOTIFICATION = "Push Notification Error";

    public static function send_by_uid($uids , $notification){
        $id_notification = Notification::create($notification)->id;
        if($id_notification) {
            $user = explode(',', $uids);
            $detail_data = [];
            foreach ($user as $uid) {
                $users = UserToken::select('token')->where("uid", '=', $uid)->first();
                if ($users) {
                    $detail_data[] = array(
                        'uid' => $uid,
                        'notification_id' => $id_notification,
                        'is_read' => false,
                        'created_at' => date('Y-m-d h:m:s'),
                    );

                    $data = array(
                        'to' => $users->token,
                        'notification' => array(
                            "title" => $notification['title'],
                            "body" => $notification['body'],
                            "image" => $notification['img'],
                            "click_action" => $notification['deeplink']
                        )
                    );

                    self::fcm_connect($data);
                }

                if(!NotificationDetail::insert($detail_data)){
                    return false;
                }
            }
        }else{
            return false;
        }
        return true;
    }

    public static function send_all_user($notification){
        $id_notification = Notification::create($notification)->id;
        if($id_notification){
            $data = array(
                'to' => "/topics/REVAMP_CASHTREE",
                'notification' => array(
                    "title"=> $notification['title'],
                    "body" =>$notification['body'],
                    "image" => $notification['img'],
                    "click_action" => $notification['deeplink']
                )
            );
            self::fcm_connect($data);
        }else{
            return false;
        }
        return true;
    }

    public static function notification($token, $title, $body, $img, $deeplink , $noty = 'noty'){
        $data = array(
            'to' => $token,
            'notification' => array(
                "type" => $noty,
                "title"=> $title,
                "body" => $body,
                "image" => $img,
                "click_action" => $deeplink
            )
        );
        self::fcm_connect($data);
        return $data;
    }

    static function fcm_connect($data){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.env('FCM_SERVER_KEY','')
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);

        if ($result === FALSE) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return true;

    }
}

