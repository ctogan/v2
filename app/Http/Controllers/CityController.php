<?php

namespace App\Http\Controllers;

use App\City;
use App\Helpers\CtreeCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use View;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $province_id = $request->province;
        $pageVars = [
            "province_id" => $province_id
        ];

        return View::make('master.city')->with($pageVars);
    }

    public function add(Request $request)
    {
        $province = Cache::get_province();
        $province_id = $request->province;

        $pageVars = [
            "province" => $province,
            "province_id" => $province_id
        ];

        return View::make('master.city_add')->with($pageVars);
    }

    public function edit($id)
    {
        $city = City::where('id','=',$id)->first();
        $province = Cache::get_province();

        $pageVars = [
            "province" => $province,
            "city"=>$city
        ];

        return View::make('master.city_edit')->with($pageVars);
    }

    public function submit(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'city_name' => 'required',
            'province_id' => 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $exist = City::where('province_id','=',$request->province_id)
                    ->where('city_name','=',$request->city_name)->first();

        if($exist){
            return json_encode(['status'=> false, 'message'=> [array("The city name has already been taken.")]]);
        }

        $data_insert = array(
            'row_status' => "active",
            'city_name' => $request->city_name,
            'province_id' => $request->province_id,
            'created_by' => Auth::user()->name,
            'created_at' => date('yy-m-d h:m:s'),
        );

        City::insert($data_insert);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'city_name' => 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $city = City::where('id','=',$request->id)->first();

        if(!$city){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        if($city->city_name != $request->city_name)
        {
            $exist = City::where('province_id','=',$request->province_id)
                ->where('city_name','=',$request->city_name)->first();

            if($exist){
                return json_encode(['status'=> false, 'message'=> [array("The city name has already been taken.")]]);
            }

            $city->city_name = $request->city_name;
            $city->province_id = $request->province_id;
            $city->updated_by = Auth::user()->name;
            $city->updated_at = date('yy-m-d h:m:s');

            if(!$city->save()){
                return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
            }
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request)
    {
        $city = City::where('id','=',$request->id)->first();

        if(!$city){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $city->row_status = "deleted";
        $city->updated_by = Auth::user()->name;
        $city->updated_at = date('yy-m-d h:m:s');

        if(!$city->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request)
    {
        $query = City::where('city.row_status','=',"active")
            ->join('province','province.id','city.province_id')
            ->select('city.*','province.province_name');

        if($request->province_id){
            $query->where('province_id','=',$request->province_id);
        }

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
