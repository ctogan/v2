<?php

namespace App\Http\Controllers\Webapp;

use App\CCQuestion;
use App\CCSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CerdasCermatController extends Controller
{
    public function index(Request $request){
        $this->session($request);

        $mmses = $request->mmses;

        $data = [
            'mmses' => $mmses
        ];

        return view('webapp.cerdas-cermat.index', $data);
    }

    public function start(Request $request){
        $session = CCSession::where('session_code','=', $request->code)->first();
        $question = CCQuestion::where('session_id','=',$session->id)->get();

        $data = [
            'session' => $session,
            'question' => $question,
        ];

        return view('webapp.cerdas-cermat.start', $data);
    }
}
