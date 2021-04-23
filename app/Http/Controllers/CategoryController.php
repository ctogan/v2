<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.operation.setting.category.index');
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'category_name' => 'required',
            'img' => 'required|image|mimes:jpg,jpeg,png,webapp'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data = [
            'row_status' => 'active',
            'category_name' => $request->category_name,
            'deeplink' => $request->deeplink,
            'img' => Utils::upload($request,'img','category/'),
            'created_by' => Auth::user()->name,
            'created_at' => date('Y-m-d h:m:s'),
        ];

        Category::insert($data);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if($validation->fails()){
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $category = Category::find($request->id);

        if(!$category){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Category Not Found')]);
        }

        if($request->img){
            $validation = Validator::make($request->all(), [
                'img' => 'required|image|mimes:jpg,jpeg,png,webapp'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $category->img = Utils::upload($request,'img','category/');
        }

        $category->row_status = $request->row_status;
        $category->category_name = $request->category_name;
        $category->deeplink = $request->deeplink;
        $category->updated_by = Auth::user()->name;
        $category->updated_at = date('Y-m-d h:m:s');

        if(!$category->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request){
        $category = Category::find($request->id);

        if(!$category){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Category Not Found')]);
        }

        $category->row_status = 'deleted';
        $category->updated_by = Auth::user()->name;
        $category->updated_at = date('Y-m-d h:m:s');

        if(!$category->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function get(Request $request){
        $category = Category::find($request->id);

        return json_encode(['status'=> true, 'data'=> $category]);
    }

    public function update_position(Request $request){
        if($request->positions){
            foreach ($request->positions as $position){
                $id = $position[0];
                $new_position = $position[1];

                Category::where('id','=',$id)->update(
                    array(
                        'sequence'=> $new_position,
                        'updated_at' => date('Y-m-d h:m:s'),
                        'updated_by' => Auth::user()->name
                    )
                );
            }
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function paging(Request $request){
        $query = Category::where('row_status','!=','deleted');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
