<?php

namespace App\Http\Controllers;

use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use View;

class ProvinceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('master.province');
    }

    public function add()
    {
        return view('master.province_add');
    }

    public function edit($id)
    {
        $province = Province::where('id','=',$id)->first();

        $pageVars = [
            "province" => $province
        ];

        return View::make('master.province_edit')->with($pageVars);
    }

    public function submit(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'province_name' => 'required|unique:common.province',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data_insert = array(
            'row_status' => "active",
            'province_name' => $request->province_name,
            'created_by' => Auth::user()->name,
            'created_at' => date('yy-m-d h:m:s'),
        );

        Province::insert($data_insert);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'province_name' => 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $province = Province::where('id','=',$request->id)->first();

        if(!$province){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        if($province->province_name != $request->province_name)
        {
            $validation = Validator::make($request->all(), [
                'province_name' => 'unique:province',
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $province->province_name = $request->province_name;
            $province->updated_by = Auth::user()->name;
            $province->updated_at = date('yy-m-d h:m:s');

            if(!$province->save()){
                return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
            }
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request){
        $province = Province::where('id','=',$request->id)->first();

        if(!$province){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $province->row_status = "deleted";
        $province->updated_by = Auth::user()->name;
        $province->updated_at = date('yy-m-d h:m:s');

        if(!$province->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request)
    {
        return DataTables::of(Province::where('row_status','=',"active")
            ->get())->addIndexColumn()->make(true);
    }
}
