<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Helpers\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.operation.setting.banner.index');
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'banner_name' => 'required',
            'img' => 'required|image|mimes:jpg,jpeg,png,webapp'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data = [
            'row_status' => 'active',
            'banner_name' => $request->banner_name,
            'deeplink' => $request->deeplink,
            'img' => Utils::upload($request,'img','banner/'),
            'created_by' => Auth::user()->name,
            'created_at' => date('Y-m-d h:m:s'),
        ];

        Banner::insert($data);

        $this->forget_cache('__banner_section');
        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'banner_name' => 'required',
        ]);

        if($validation->fails()){
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $banner = Banner::find($request->id);

        if(!$banner){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Banner Not Found')]);
        }

        if($request->img){
            $validation = Validator::make($request->all(), [
                'img' => 'required|image|mimes:jpg,jpeg,png,webapp'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $banner->img = Utils::upload($request,'img','banner/');
        }

        $banner->row_status = $request->row_status;
        $banner->banner_name = $request->banner_name;
        $banner->deeplink = $request->deeplink;
        $banner->updated_by = Auth::user()->name;
        $banner->updated_at = date('Y-m-d h:m:s');

        if(!$banner->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
        }
        $this->forget_cache('__banner_section');

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request){
        $banner = Banner::find($request->id);

        if(!$banner){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Banner Not Found')]);
        }

        $banner->row_status = 'deleted';
        $banner->updated_by = Auth::user()->name;
        $banner->updated_at = date('Y-m-d h:m:s');

        if(!$banner->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
        }
        $this->forget_cache('__banner_section');

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function get(Request $request){
        $banner = Banner::find($request->id);

        return json_encode(['status'=> true, 'data'=> $banner]);
    }

    public function update_position(Request $request){
        if($request->positions){
            foreach ($request->positions as $position){
                $id = $position[0];
                $new_position = $position[1];

                Banner::where('id','=',$id)->update(
                    array(
                        'sequence'=> $new_position,
                        'updated_at' => date('Y-m-d h:m:s'),
                        'updated_by' => Auth::user()->name
                    )
                );
            }
        }

        $this->forget_cache('__banner_section');
        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request){
        $query = Banner::where('row_status','!=','deleted');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
