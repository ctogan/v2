<?php

namespace App\Http\Controllers;

use App\SmsBlast;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Sessions;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactImport;
use Nasution\Zenziva\Zenziva;


class OperationalController extends Controller
{
    function clbk_event(){

        $sms_list = SmsBlast::get();

        $pageVars = [
            "sms_list" => $sms_list
        ];
        return View::make('operational.clbk.index')->with($pageVars);
    }
    function clbk_upload(){

        return View::make('operational.clbk.add');
    }

    public function clbk_submit(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        $file_name = rand().$file->getClientOriginalName();
        $file->move('upload',$file_name);
        Excel::import(new ContactImport, public_path('/upload/'.$file_name));
        return json_encode(['status'=> true, 'message'=> "success"]);
    }

    public function sms_test(){

//        $zenziva = new Zenziva('dwzrko', 'kotakasabla');
//        $zenziva->sms('085275608369', 'Halo');
//        echo "berhasil kirim";

        /*
		$url = 'https://reguler.zenziva.net/apps/smsotp.php?'.http_build_query([
				'userkey' => static::ZENZIVA_USER_KEY,
				'passkey' => static::ZENZIVA_PASS_KEY,
				'nohp' => $num,
//				'pesan' => $otp,
                'kode_otp' => $otp
			]);*/

        $userkey = "dwzrko"; //userkey lihat di zenziva
        $passkey = "kotakasabla"; // set passkey di zenziva
        $telepon = "085275608369";
        $message = "Terima Kasih, pendaftaran atas nama  telah berhasil di websiteAnda.com. Silahkan baca dan download petunjuk selanjutnya. Harap Maklum";
        $url = "https://gsm.zenziva.net/api/sendsms/";
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'userkey' => $userkey,
            'passkey' => $passkey,
            'nohp' => $telepon,
            'pesan' => $message
        ));
        $results = json_decode(curl_exec($curlHandle), true);
        curl_close($curlHandle);

        echo "berhasil";
    }

}
