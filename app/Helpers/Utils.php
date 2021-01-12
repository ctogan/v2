<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Utils {

    protected const DEF_ENC_KEY = "aresjoyminiramdoaresjoyminiramdo";
    public const RELIGION_MASTER = ['1'=>'Kristen','2'=>'Islam','3'=>'Hindu','4'=>'Budha','5'=>'Katolik'];
    public const EDUCATION_MASTER = ['sd'=>'SD','smp'=>'SMP','d1'=>'D1','d2'=>'D2','d3'=>'D3','d4'=>'D4','s1'=>'S1','s2'=>'S2','s3'=>'S3'];

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
            "rejected"=>"badge-dark"
        );

        return $badge[$status];
    }

    public static function decrypt($base64_text, $hex = false) {
        if ($hex) {
            $base64_text = hex2bin(strtolower($base64_text));
        }
        return openssl_decrypt(base64_decode($base64_text), 'aes-256-cbc', static::DEF_ENC_KEY, true, str_repeat(chr(0), 16));
    }

}
