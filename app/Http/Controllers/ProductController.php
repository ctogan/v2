<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.product.index');
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'product_code' => 'required',
            'product_name' => 'required',
        ]);

        if($validation->fails()){
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $product = Product::find($request->id);

        if(!$product){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Banner Not Found')]);
        }

        if($request->img){
            $validation = Validator::make($request->all(), [
                'img' => 'required|image|mimes:jpg,jpeg,png,webapp'
            ]);

            if($validation->fails()) {
                return json_encode(['status'=> false, 'message'=> $validation->messages()]);
            }

            $product->img = Utils::upload($request,'img','product/');
        }

        $product->row_status = $request->row_status;
        $product->product_code = $request->product_code;
        $product->product_name = $request->product_name;
        $product->updated_by = Auth::user()->name;
        $product->updated_at = date('Y-m-d h:m:s');

        if(!$product->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function get(Request $request){
        $product = Product::find($request->id);

        return json_encode(['status'=> true, 'data'=> $product]);
    }

    public function paging(Request $request){
        $query = Product::where('row_status','!=','deleted');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }
}
