<?php

namespace App\Http\Controllers;

use App\CompanyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use View;

class CompanyCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('master.company_category');
    }

    public function add()
    {
        return view('master.company_category_add');
    }

    public function edit($id)
    {
        $category = CompanyCategory::where('id','=',$id)->first();

        $pageVars = [
            "category" => $category,
        ];

        return View::make('master.company_category_edit')->with($pageVars);
    }

    public function submit(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_name' => 'required|unique:common.company_category',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data_insert = array(
            'row_status' => "active",
            'category_name' => $request->category_name,
            'created_by' => Auth::user()->name,
            'created_at' => date('yy-m-d h:m:s'),
        );

        CompanyCategory::insert($data_insert);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $category = CompanyCategory::where('id','=',$request->id)->first();

        if(!$category){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        if($category->category_name != $request->category_name){
            $validation = Validator::make($request->all(), [
                'category_name' => 'unique:company_category',
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }
        }

        $category->category_name = $request->category_name;
        $category->updated_by = Auth::user()->name;
        $category->updated_at = date('yy-m-d h:m:s');

        if(!$category->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request)
    {
        $category = CompanyCategory::where('id','=',$request->id)->first();

        if(!$category){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $category->row_status = "deleted";
        $category->updated_by = Auth::user()->name;
        $category->updated_at = date('yy-m-d h:m:s');

        if(!$category->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request){
        return DataTables::of(CompanyCategory::where('row_status','=','active')
            ->get())->addIndexColumn()->make(true);
    }
}
