<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CerdasCermatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        return view('admin.cerdas-cermat.index');
    }

    public function add(Request $request){
        return view('admin.cerdas-cermat.add');
    }
}
