<?php

namespace App\Http\Controllers;

use App\Jobs\SendSmsJob;
use App\SmsBlast;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Sessions;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactImport;
use Illuminate\Support\Facades\Validator;


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

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        if ($validator->fails()) {
            return json_encode(['status'=> false, 'message'=> $validator->messages()]);
        }

        $file = $request->file('file');
        $file_name = rand().$file->getClientOriginalName();
        $file->move('upload',$file_name);
        Excel::import(new ContactImport, public_path('/upload/'.$file_name));
        return json_encode(['status'=> true, 'message'=> "success"]);
    }

    public function do_sms_queue(){

        $users = SmsBlast::where('status','pending')->get();
        foreach ($users as $key=> $user){
            SendSmsJob::dispatch($user->phone_number, $user->message,$user->uid);

        }
        return redirect('/admin/operational/clbk/event');


    }

}
