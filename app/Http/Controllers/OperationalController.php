<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Jobs\SendSmsJob;
use App\LayoutSetting;
use App\SmsBlast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;
use Illuminate\Support\Facades\Sessions;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactImport;
use Illuminate\Support\Facades\Validator;


class OperationalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
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

    public function theme(Request $request){
        $layout = LayoutSetting::get();
        $backgound_color = DB::connection('common')->table('settings')->where('setting_code','=', 'background_color')->first();
        $backgound_image = DB::connection('common')->table('settings')->where('setting_code','=', 'background_image')->first();
        $layout_content = DB::connection('common')->table('settings')->where('setting_code','=', 'layout_content')->first();

        $data = [
            'layout' => $layout,
            'background_color' => $backgound_color,
            'background_image' => $backgound_image,
            'layout_content' => $layout_content,
        ];

        return view('admin.operation.setting.theme.index', $data);
    }

    public function theme_update(Request $request){
        $validation = Validator::make($request->all(), [
            'categories' => 'required',
            'flash_event' => 'required',
            'unfinished' => 'required',
            'dynamic' => 'required',
            'news' => 'required',
            'layout_content' => 'required',
            'background_color' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        if($request->background_image){
            $validation = Validator::make($request->all(), [
                'background_image' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            DB::connection('common')->table('settings')->where('setting_code','=', 'background_image')->update(['setting_value_full'=> Utils::upload($request,'background_image','settings')]);
        }

        DB::connection('common')->table('settings')->where('setting_code','=', 'background_color')->update(['setting_value_full'=> $request->background_color]);
        DB::connection('common')->table('settings')->where('setting_code','=', 'layout_content')->update(['setting_value_full'=> $request->layout_content]);

        $categories = LayoutSetting::where('page_name','=','categories')->first();
        $categories->sequence = $request->categories;
        $categories->save();
        $flash_event = LayoutSetting::where('page_name','=','flash_event')->first();
        $flash_event->sequence = $request->flash_event;
        $flash_event->save();
        $unfinished =LayoutSetting::where('page_name','=','unfinished')->first();
        $unfinished->sequence = $request->unfinished;
        $unfinished->save();
        $dynamic =LayoutSetting::where('page_name','=','dynamic')->first();
        $dynamic->sequence = $request->dynamic;
        $dynamic->save();
        $news =LayoutSetting::where('page_name','=','news')->first();
        $news->sequence = $request->news;
        $news->save();

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }
}
