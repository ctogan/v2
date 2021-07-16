<?php

namespace App\Http\Controllers;

use App\Helpers\Push;
use App\Helpers\Utils;
use App\Notification;
use App\NotificationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.notification.index');
    }

    public function send(Request $request){
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'target' => 'required',
            'deeplink' => 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        if($request->target == 'uid'){
            $validation = Validator::make($request->all(), [
                'uid' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
        }

        $image = null;
        if($request->notification_img){
            $validation = Validator::make($request->all(), [
                'notification_img' => 'required|image|mimes:jpg,jpeg,png,webapp'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $image = Utils::upload($request, 'notification_img', 'notification/');
        }

        $notification = [
            'title' => $request->title,
            'deeplink' => $request->deeplink,
            'body' => $request->body,
            'row_status' => 'active',
            'img' => $image,
            'send_to' => $request->target,
            'send_by' => 'admin',
            'created_at' => date('Y-m-d H:m:s'),
            'created_by' => Auth::user()->name
        ];

        if($request->target == 'uid'){
            if(!Push::send_by_uid($request->uid, $notification)){
                return json_encode(['status'=> false, 'message'=> "failed"]);
            }
        }else{
            Push::send_all_user($notification);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function detail(Request $request){
        $notification = Notification::find($request->id);
        $data = [
            'notification' => $notification
        ];

        return view('admin.notification.detail', $data);
    }

    public function paging(Request $request){
        $query = Notification::where('row_status','!=','deleted')->where('send_by','=','admin');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }

    public function paging_detail(Request $request){
        $query = NotificationDetail::where('notification_id','=',$request->id);

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
