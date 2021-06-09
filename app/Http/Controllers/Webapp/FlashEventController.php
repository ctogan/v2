<?php

namespace App\Http\Controllers\Webapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashEventController extends Controller
{
    public function detail(Request $request){
        $data = [

        ];

        return view('webapp.flash-event.detail', $data);
    }
}
