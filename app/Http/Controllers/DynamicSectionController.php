<?php

namespace App\Http\Controllers;

use App\DynamicSection;
use App\Helpers\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DynamicSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.dynamic-section.index');
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'target'=> 'required',
            'dynamic_section_img' => 'required|image|mimes:jpg,jpeg,png,webapp'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data = [
            'row_status'=> $request->row_status,
            'title'=> $request->title,
            'sub_title'=> $request->sub_title,
            'target'=> $request->target,
            'dynamic_section_img'=> Utils::upload($request,'dynamic_section_img', 'dynamicsection/'),
            'created_by' => Auth::user()->name,
            'created_at' => date('Y-m-d h:m:s'),
        ];
        if($request->target == 'inapp' || $request->target  == 'default_browser'){
            $validation = Validator::make($request->all(), [
                'url' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $data['url'] = $request->url;

        }else if($request->target  == 'deeplink'){
            $validation = Validator::make($request->all(), [
                'deeplink' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $data['deeplink'] = $request->deeplink;
        }else if($request->target  == 'snapcash'){
            $validation = Validator::make($request->all(), [
                'snapcash_id' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $data['snapcash_id'] = $request->snapcash_id;
        }else if($request->target === 'campaign'){
            $validation = Validator::make($request->all(), [
                'adid' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $data['adid'] = $request->adid;
        }

        DynamicSection::insert($data);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required',
            'target'=> 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $dynamic_section = DynamicSection::where('id','=',$request->id)->first();

        if(!$dynamic_section){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Data not found!')]);
        }

        $dynamic_section->row_status= $request->row_status;
        $dynamic_section->title= $request->title;
        $dynamic_section->sub_title= $request->sub_title;
        $dynamic_section->target= $request->target;
        $dynamic_section->updated_by = Auth::user()->name;
        $dynamic_section->updated_at = date('Y-m-d h:m:s');

        if($request->dynamic_section_img){
            $validation = Validator::make($request->all(), [
                'dynamic_section_img' => 'required|image|mimes:jpg,jpeg,png,webapp'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $dynamic_section->dynamic_section_img = Utils::upload($request,'dynamic_section_img', 'dynamicsection/');
        }

        if($request->target == 'inapp' || $request->target  == 'default_browser'){
            $validation = Validator::make($request->all(), [
                'url' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $dynamic_section->url = $request->url;

        }else if($request->target  == 'deeplink'){
            $validation = Validator::make($request->all(), [
                'deeplink' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $data['deeplink'] = $request->deeplink;
        }else if($request->target  == 'snapcash'){
            $validation = Validator::make($request->all(), [
                'snapcash_id' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $dynamic_section->snapcash_id = $request->snapcash_id;
        }else if($request->target === 'campaign'){
            $validation = Validator::make($request->all(), [
                'adid' => 'required'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
            $dynamic_section->adid = $request->adid;
        }

        if(!$dynamic_section->save()){
            return json_encode(['status'=> true, 'message'=> $this->single_message('Server Error')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request){
        $query = DynamicSection::where('row_status','!=','deleted');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }

    public function edit(Request $request){
        $data = DynamicSection::find($request->id);

        return json_encode(['status'=> true, 'data'=> $data]);
    }

    public function delete(Request $request){
        $ds = DynamicSection::find($request->id);

        if(!$ds){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Category Not Found')]);
        }

        $ds->row_status = 'deleted';
        $ds->updated_by = Auth::user()->name;
        $ds->updated_at = date('Y-m-d h:m:s');

        if(!$ds->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }
}
