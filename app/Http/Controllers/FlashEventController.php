<?php

namespace App\Http\Controllers;

use App\FlashEvent;
use App\FlashEventDetail;
use App\Helpers\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class FlashEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.flash-event.index');
    }

    public function add(Request $request){
        return view('admin.flash-event.add');
    }

    public function edit(Request $request){
        $flash_event = FlashEvent::with('detail')->where('id','=', $request->id)->first();
        $data=[
            'flash_event' =>$flash_event
        ];

        return view('admin.flash-event.edit', $data);
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'event_name' => 'required',
            'event_description'=> 'required',
            'event_period' => 'required',
            'event_img' => 'required|image|mimes:jpg,jpeg,png,webapp',
            'event_tnc'=> 'required',
            'detail' => 'required',
            'time_from' => 'required',
            'time_to' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        if($request->event_period == 'weekly'){
            $validation = Validator::make($request->all(), [
                'day'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
        }

        $date_from =null;
        $date_to =null;
        if($request->event_period == 'special_date'){
            $validation = Validator::make($request->all(), [
                'event_date'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $arr_date = explode('-', $request->event_date);
            $str_date_from = str_replace('/','-',$arr_date[0]);
            $str_date_to = str_replace('/','-',$arr_date[1]);

            $date_from = date_format(date_create($str_date_from),"Y-m-d");
            $date_to = date_format(date_create($str_date_to),"Y-m-d");
        }

        if($request->time_from >= $request->time_to){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Time To must be greater than Time From')]);
        }

        $data = [
            'event_code' => Str::random(8),
            'event_name' => $request->event_name,
            'event_description'=> $request->event_description,
            'event_period' => $request->event_period,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'day_name' => $request->day_name,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'event_img' => Utils::upload($request,'event_img','flash-event/'),
            'event_tnc'=> $request->event_tnc,
            'ut_by_register_date'=>$request->ut_by_register_date == 'true' ? true : false,
            'ut_by_point_count'=>$request->ut_by_point_count == 'true' ? true : false,
            "is_tester" => $request->availability,
            'created_by' => Auth::user()->name,
            'created_at' => date('Y-m-d h:m:s'),
        ];

        if($request->ut_by_register_date == 'true'){
            $validation = Validator::make($request->all(), [
                'target_registered'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $arr_date_register = explode('-', $request->target_registered);
            $registered_from = str_replace('/','-',$arr_date_register[0]);
            $registered_to = str_replace('/','-',$arr_date_register[1]);

            $data['registered_from'] = date_format(date_create($registered_from),"Y-m-d");
            $data['registered_to'] = date_format(date_create($registered_to),"Y-m-d");
        }

        if($request->ut_by_point_count  == 'true'){
            $validation = Validator::make($request->all(), [
                'target_point_from'=> 'required',
                'target_point_to'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            if($request->target_point_from > $request->target_point_to){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Target Point To must be greater than Target Time From')]);
            }

            $data['target_point_from'] = $request->target_point_from;
            $data['target_point_to'] = $request->target_point_to;
        }

        $details = $request->detail;
        $data_field = array();
        foreach ($details as $item){
            if(!$item['product_id']){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Product is Mandatory')]);
            }
            if(!$item['point']){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Point is Mandatory')]);
            }
            if(!$item['cap']){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Cap is Mandatory')]);
            }
            $item['flash_detail_code'] = Str::random(8);
            $item['flash_event_id'] = 0;
            array_push($data_field,$item);
        }

        DB::beginTransaction();
        try{
            $flash_event = FlashEvent::create($data);
            if($flash_event){
                foreach ($data_field as &$item){
                    $item['flash_event_id'] = $flash_event->id;
                }
                FlashEventDetail::insert($data_field);
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return json_encode(['status'=> false, 'message'=> $this->single_message('Server Error')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'event_name' => 'required',
            'event_description'=> 'required',
            'event_period' => 'required',
            'event_tnc'=> 'required',
            'detail' => 'required',
            'time_from' => 'required',
            'time_to' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $flash_event = FlashEvent::where('id','=',$request->id)->first();

        if(!$flash_event){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Data not found!')]);
        }

        if($request->event_period == 'weekly'){
            $validation = Validator::make($request->all(), [
                'day'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $flash_event->day_name = $request->day_name;
        }

        $date_from =null;
        $date_to =null;
        if($request->event_period == 'special_date'){
            $validation = Validator::make($request->all(), [
                'event_date'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $arr_date = explode('-', $request->event_date);
            $date_from = str_replace('/','-',$arr_date[0]);
            $date_to = str_replace('/','-',$arr_date[1]);

            $flash_event->date_from = date_format(date_create($date_from),"Y-m-d");
            $flash_event->date_to = date_format(date_create($date_to),"Y-m-d");
        }

        if($request->time_from >= $request->time_to){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Time To must be greater than Time From')]);
        }

        if($request->ut_by_register_date == 'true'){
            $validation = Validator::make($request->all(), [
                'target_registered'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $arr_date_register = explode('-', $request->target_registered);
            $registered_from = str_replace('/','-',$arr_date_register[0]);
            $registered_to = str_replace('/','-',$arr_date_register[1]);

            $flash_event->registered_from = date_format(date_create($registered_from),"Y-m-d");
            $flash_event->registered_to = date_format(date_create($registered_to),"Y-m-d");
        }

        if($request->ut_by_point_count  == 'true'){
            $validation = Validator::make($request->all(), [
                'target_point_from'=> 'required',
                'target_point_to'=> 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            if($request->target_point_from > $request->target_point_to){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Target Point To must be greater than Target Time From')]);
            }

            $flash_event->target_point_from = $request->target_point_from;
            $flash_event->target_point_to = $request->target_point_to;
        }

        $flash_event->event_name = $request->event_name;
        $flash_event->event_description = $request->event_description;
        $flash_event->event_period = $request->event_period;
        $flash_event->time_from = $request->time_from;
        $flash_event->time_to = $request->time_to;
        if($request->event_img){
            $validation = Validator::make($request->all(), [
                'event_img' => 'required|image|mimes:jpg,jpeg,png,webapp',
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $flash_event->event_img = Utils::upload($request,'event_img','flash-event/');
        }
        $flash_event->event_tnc = $request->event_tnc;
        $flash_event->ut_by_register_date = $request->ut_by_register_date == 'true' ? true : false;
        $flash_event->ut_by_point_count =$request->ut_by_point_count == 'true' ? true : false;
        $flash_event->is_tester = $request->availability;
        $flash_event->updated_by = Auth::user()->name;
        $flash_event->updated_at = date('Y-m-d h:m:s');

        $details = $request->detail;
        $data_field = array();
        foreach ($details as $item){
            if(!$item['product_id']){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Product is Mandatory')]);
            }
            if(!$item['point']){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Point is Mandatory')]);
            }
            if(!$item['cap']){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Cap is Mandatory')]);
            }
            if(!isset($item['flash_detail_code'])){
                $item['flash_detail_code'] = Str::random(8);
            }
            $item['flash_event_id'] = $flash_event->id;
            array_push($data_field,$item);
        }

        DB::beginTransaction();
        try{
            if($flash_event->save()){
                foreach ($data_field as $item){
                    FlashEventDetail::updateOrCreate(['flash_event_id' => $flash_event->id, 'flash_detail_code'=> $item['flash_detail_code']], $item);
                }
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return json_encode(['status'=> false, 'message'=> $this->single_message($e->getMessage())]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request){
        $flash_event = FlashEvent::find($request->id);

        if(!$flash_event){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Category Not Found')]);
        }

        $flash_event->row_status = 'deleted';
        $flash_event->updated_by = Auth::user()->name;
        $flash_event->updated_at = date('Y-m-d h:m:s');

        if(!$flash_event->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete_detail(Request $request){
        $detail = FlashEventDetail::where('flash_detail_code','=',$request->code)->first();

        if($detail){
            $detail->row_status = 'deleted';
            $detail->updated_by = Auth::user()->name;
            $detail->updated_at = date('Y-m-d h:m:s');

            if(!$detail->save()){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
            }
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request){
        $query = FlashEvent::where('row_status','!=','deleted')->withCount('detail');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
