<?php

namespace App\Http\Controllers\Webapp;

use App\CCQuestion;
use App\CCSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function free_trial(Request $request){
        $this->session($request);

        $question = CCQuestion::inRandomOrder()->where('row_status','=','active')->take(10)->get();
        $data = [
            'session_id' => Str::random(8),
            'question_count' => 10,
            'page' => 1,
            'question' => $question
        ];
        return view('webapp.cerdas-cermat.free', $data);
    }
}
