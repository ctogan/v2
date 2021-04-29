<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Utils {

    protected const DEF_ENC_KEY = "aresjoyminiramdoaresjoyminiramdo";
    public const RELIGION_MASTER = [['id'=>'1','name'=>'Kristen'],['id'=>'2','name'=>'Islam'],['id'=>'3','name'=>'Hindu'],['id'=>'4','name'=>'Budha'],['id'=>'5','name'=>'Katolik']];
    public const EDUCATION_MASTER = [['name'=>'SD'],['name'=>'SMP'],['name'=>'SMA'],['name'=>'D1'],['name'=>'D2'],['name'=>'D3'],['name'=>'D4'],['name'=>'S1'],['name'=>'S2'],['name'=>'S3']];
    public const SEX = ['Pria','Wanita'];

    public static function upload(Request $request, $name, $path) {
        $file = $request->file($name);
        $file_name =uniqid().'.'.$file->getClientOriginalExtension();
        $file_path = $path . $file_name;
        Storage::disk('s3')->put($file_path, file_get_contents($file));

        return env('AWS_BUCKET_URL').$file_path;
    }

    public static function get_vacancy_badge($status){
        $badge = array(
            "pending" => "badge-warning",
            "published" => "badge-primary",
            "unpublished" => "badge-info text-light",
            "rejected"=>"badge-dark",
            "waiting_confirm"=>"badge-warning p-2",
        );

        return $badge[$status];
    }

    public static function decrypt($base64_text, $hex = false) {
        if ($hex) {
            $base64_text = hex2bin(strtolower($base64_text));
        }
        return openssl_decrypt(base64_decode($base64_text), 'aes-256-cbc', static::DEF_ENC_KEY, true, str_repeat(chr(0), 16));
    }

    public static function mark_down($input){
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
    }

    public static function ad_part($i){
        return 'ad_part_'.$i;
    }
}
