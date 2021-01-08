<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use View;

class UserAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::where('row_status','=','active')->get();

        $pageVars = [
            'users' => $user
        ];
        return View::make('admin.settings.user_management')->with($pageVars);
    }

    public function add()
    {
        return View::make('admin.settings.user_management_add');
    }

    public function edit($id)
    {
        $user = User::where('id','=',$id)->first();

        $pageVars = [
            'user' => $user
        ];
        return View::make('admin.settings.user_management_edit')->with($pageVars);
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password_confirmation' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data_insert = array(
            'row_status' => "active",
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password_confirmation),
            'created_by' => Auth::user()->name,
            'created_at' => date('yy-m-d h:m:s'),
        );

        User::insert($data_insert);

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = User::where('id','=',$request->id)->first();

        if(!$user){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $user->name = $request->name;
        $user->updated_by = Auth::user()->name;
        $user->updated_at = date('yy-m-d h:m:s');

        if(!$user->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete(Request $request){
        $user = User::where('id','=',$request->id)->first();

        if(!$user){
            return json_encode(['status'=> false, 'message'=> [array("Data Not Found!")]]);
        }

        $user->row_status = "deleted";
        $user->updated_by = Auth::user()->name;
        $user->updated_at = date('yy-m-d h:m:s');

        if(!$user->save()){
            return json_encode(['status'=> false, 'message'=> [array("Update Error!")]]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function change_password(Request $request){
        $validation = Validator::make($request->all(), [
            'old_password' => 'required',
            'password_confirmation' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> 'false', 'message'=> $validation->messages()]);
        }

        $user = User::where('id','=',Auth::user()->id)->first();
        if(Hash::make($request->old_password) != $user->password){
            return json_encode(['status'=> 'false', 'message'=> array(["Old Password does not match"])]);
        }

        $user->password = Hash::make($request->password_confirmation);
        $user->updated_at = date('yy-m-d h:m:s');
        $user->updated_by = Auth::user()->name;

        if($user->save()){
            return json_encode(['status'=> 'true', 'message'=> 'success']);
        }
    }
}
