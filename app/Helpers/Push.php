<?php
namespace App\Helpers;

use App\Notifications;
use App\NotificationsDetails;
use App\UserInbox;
use App\UserView;

class Push {

    protected const ERR_PUSH_NOTIFICATION = "Push Notification Error";
    protected const FCM_SERVER_KEY = 'AAAARIBZvgU:APA91bE7hhOF4JWOrqqAiLzQL2ZrFJtQQQew69tnsA55tpx1UNX9LmOMMQSBFFjq-vT6zdQ0B_nna-mcIwfF5uacbfP7rGCHnSa2X8EESqT-Yzg4hPKjrKd3O4eeCZbvqn6Kg7aDfioA';

    public static function send_by_uid($id , $notification){
        $id_notification = Notifications::create($notification)->id;
        if($id_notification) {
            $user = explode(',', $id);
            $detail_data = [];
            foreach ($user as $uid) {
                $users = UserView::select('push_token')->where("uid", '=', $uid)->first();
                if ($users) {
                    $detail_data[] = array(
                        'uid' => $uid,
                        'notification_id' => $id_notification,
                        'status' => 'unread',
                        'created_at' => date('yy-m-d h:m:s'),
                    );
                    $data = array(
                        'to' => $users->push_token,
                        'notification' => array(
                            "title" => $notification['title'],
                            "body" => $notification['body'],
                            "image" => $notification['img'],
                            "click_action" => $notification['deeplink']
                        )
                    );
                    if (self::fcm_connect(json_encode($data))) {
                        $update_notif = Notifications::where('id', $id_notification)
                            ->first();
                        $update_notif->status = 'sent';
                        $update_notif->save();
                    } else {
                        return false;
                    }
                }
                if(!NotificationsDetails::insert($detail_data)){
                    return false;
                }
            }
        }else{
            return false;
        }
        return true;
    }

    public static function send_all_user($notification){
        $id_notification = Notifications::create($notification)->id;
        if($id_notification){
            $data = array(
                'to' => "/topics/PROMO",
                'notification' => array(
                    "title"=> $notification['title'],
                    "body" =>$notification['body'],
                    "image" => $notification['img'],
                    "click_action" => $notification['deeplink']
                )
            );
            if(self::fcm_connect(json_encode($data))){
                $update_notif = Notifications::where('id' , $id_notification)
                    ->first();
                $update_notif->status = 'sent';
                $update_notif->save();
            }
        }else{
            return false;
        }
        return true;
    }

    static function fcm_connect($data){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.static::FCM_SERVER_KEY
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return true;

    }
}

