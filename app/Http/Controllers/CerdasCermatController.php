<?php

namespace App\Http\Controllers;

use App\CCAnswer;
use App\CCQuestion;
use App\CCSession;
use App\CCSessionPrize;
use App\CCSessionQuestion;
use App\Helpers\Utils;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $products = Product::where('row_status','=','active')->get();
        $data = [
          'products' => $products
        ];

        return view('admin.cerdas-cermat.add',$data);
    }

    public function edit(Request $request){
        $products = Product::where('row_status','=','active')->get();
        $sesssion = CCSession::find($request->id);
        $data = [
            'products' => $products,
            'session' => $sesssion
        ];

        return view('admin.cerdas-cermat.edit',$data);
    }

    public function question_list(Request $request){
        return view('admin.cerdas-cermat.question.index');
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'registration_fee' => 'required',
            'open_date' => 'required',
            'time_start' =>'required',
            'time_end' =>'required',
            'tnc'=>'required',
            'availability'=>'required',
            'random_question'=>'required',
            'displayed_question'=>'required',
            'prize'=>'required',
            'cc_question_id'=>'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        if($request->random_question < $request->displayed_question){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Random Question must be greater or equal then Displayed Question')]);
        }

        $prizes = $request->prize;
        $arr_prize = array();
        foreach ($prizes as $item){
            if($item['rank'] == '' || $item['rank'] == 0){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Rank is mandatory')]);
            }

            if($item['item'] == ''){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Prize is mandatory')]);
            }

            $pr['row_status'] = 'active';
            $pr['cc_session_id'] = 0;
            $pr['rank'] = $item['rank'];
            $pr['product_id'] = $item['item'];
            $pr['created_by'] = Auth::user()->name;
            $pr['created_at'] = date('Y-m-d h:m:s');

            foreach ($arr_prize as $p){
                if($p['rank'] == $item['rank']){
                    return json_encode(['status'=> false, 'message'=> $this->single_message('Duplicate Rank')]);
                }
            }

            array_push($arr_prize,$pr);
        }

        $question = $request->cc_question_id;
        $arr_question = array();
        foreach ($question as $item){
            $q['cc_session_id'] = 0;
            $q['cc_question_id'] = $item;

            array_push($arr_question,$q);
        }

        $arr_session = [
            'session_code' => Str::random(8),
            'row_status' => 'active',
            'title' => $request->title,
            'registration_fee' => $request->registration_fee,
            'open_date' => $request->open_date,
            'time_start' =>$request->time_start,
            'time_end' =>$request->time_end,
            'tnc'=>$request->tnc,
            'is_tester'=>$request->availability,
            'random_question'=>$request->random_question,
            'displayed_question'=>$request->displayed_question,
            'created_by' => Auth::user()->name,
            'created_at' => date('Y-m-d h:m:s')
        ];

        DB::beginTransaction();
        try {
            $session = CCSession::create($arr_session);
            if ($session) {
                foreach ($arr_prize as &$item) {
                    $item['cc_session_id'] = $session->id;
                }
                CCSessionPrize::insert($arr_prize);

                foreach ($arr_question as &$item) {
                    $item['cc_session_id'] = $session->id;
                }

                CCSessionQuestion::insert($arr_question);

                DB::commit();
            }
        }catch (\Exception $e){
            DB::rollBack();
            return json_encode(['status'=> false, 'message'=> $this->single_message($e->getMessage())]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'row_status' => 'required',
            'title' => 'required',
            'registration_fee' => 'required',
            'open_date' => 'required',
            'time_start' =>'required',
            'time_end' =>'required',
            'tnc'=>'required',
            'availability'=>'required',
            'displayed_question'=>'required',
            'prize'=>'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $session = CCSession::find($request->id);
        if(!$session){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Data not found')]);
        }

        if($session->random_question < $request->displayed_question){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Random Question must be greater or equal then Displayed Question')]);
        }

        $prizes = $request->prize;
        $arr_new_prize = array();
        $arr_update_prize = array();
        foreach ($prizes as $item){
            if($item['rank'] == '' || $item['rank'] == 0){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Rank is mandatory')]);
            }

            if($item['item'] == ''){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Prize is mandatory')]);
            }

            $pr['row_status'] = 'active';
            $pr['cc_session_id'] = $session->id;
            $pr['rank'] = $item['rank'];
            $pr['product_id'] = $item['item'];

            foreach ($arr_new_prize as $p){
                if($p['rank'] == $item['rank']){
                    return json_encode(['status'=> false, 'message'=> $this->single_message('Duplicate Rank')]);
                }
            }

            foreach ($arr_update_prize as $p){
                if($p['rank'] == $item['rank']){
                    return json_encode(['status'=> false, 'message'=> $this->single_message('Duplicate Rank')]);
                }
            }

            if(array_key_exists('id', $item)){
                $pr['id'] = $item['id'];
                $pr['updated_by'] = Auth::user()->name;
                $pr['updated_at'] = date('Y-m-d h:m:s');
                array_push($arr_update_prize,$pr);
            }else{
                $pr['created_by'] = Auth::user()->name;
                $pr['created_at'] = date('Y-m-d h:m:s');
                array_push($arr_new_prize,$pr);
            }

            $pr=[];
        }

        $session->row_status = 'active';
        $session->title = $request->title;
        $session->registration_fee = $request->registration_fee;
        $session->open_date = $request->open_date;
        $session->time_start = $request->time_start;
        $session->time_end = $request->time_end;
        $session->tnc = $request->tnc;
        $session->is_tester = $request->availability;
        $session->displayed_question = $request->displayed_question;
        $session->updated_at = date('Y-m-d H:i');
        $session->updated_by = Auth::user()->name;

        DB::beginTransaction();
        try {
            if($session->save()){
                if(count($arr_new_prize) > 0){
                    CCSessionPrize::insert($arr_new_prize);
                }
                if(count($arr_update_prize) > 0){
                    foreach ($arr_update_prize as &$item){
                        $id_prize = $item['id'];
                        unset($item['id']);
                        CCSessionPrize::where('id','=',$id_prize)->update($item);
                    }
                }

                DB::commit();
            }
        }catch (\Exception $e){
            DB::rollBack();
            return json_encode(['status'=> false, 'message'=> $this->single_message($e->getMessage())]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function submit_question(Request $request){
        $validation = Validator::make($request->all(), [
            'question_text' => 'required',
            'question_level' => 'required',
            'answer' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $data = [
            'row_status' => 'active',
            'question' => $request->question_text,
            'question_level'=>$request->question_level,
            'created_at' => date('Y-m-d H:i'),
            'created_by' => Auth::user()->name
        ];

        if ($request->question_img){
            $data['question_image'] = Utils::upload($request,'question_img', 'ccc/');
        }

        $answers = $request->answer;
        $arr_answer = array();
        $has_correct_answer = false;
        foreach ($answers as $answer){
            if($answer['option'] == ''){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Answer is mandatory')]);
            }

            $item['row_status'] = 'active';
            $item['cc_question_id'] = 0;
            $item['answer'] = $answer['option'];
            $item['is_correct_answer'] = $answer['is_correct'];

            if($answer['is_correct'] == 'true'){
                $has_correct_answer = true;
            }
            array_push($arr_answer,$item);
        }

        if(!$has_correct_answer){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Please select the correct answer')]);
        }

        DB::beginTransaction();
        try{
            $question = CCQuestion::create($data);
            if($question){
                foreach ($arr_answer as &$item){
                    $item['cc_question_id'] = $question->id;
                }
                CCAnswer::insert($arr_answer);
            }

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return json_encode(['status'=> false, 'message'=> $this->single_message($e->getMessage())]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function update_question(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'question_text' => 'required',
            'question_level' => 'required',
            'answer' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $question = CCQuestion::find($request->id);

        if(!$question){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Data not found')]);
        }

        $question->row_status = $request->row_status;
        $question->question = $request->question_text;
        $question->question_level =$request->question_level;
        $question->updated_at = date('Y-m-d H:i');
        $question->updated_by = Auth::user()->name;

        if ($request->question_img){
            $question->question_image = Utils::upload($request,'question_img', 'ccc/');
        }

        $answers = $request->answer;
        $arr_new_answer = array();
        $arr_update_answer = array();
        $has_correct_answer = false;
        foreach ($answers as $answer){
            if($answer['option'] == ''){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Answer is mandatory')]);
            }

            $item['row_status'] = 'active';
            $item['cc_question_id'] = $question->id;
            $item['answer'] = $answer['option'];
            $item['is_correct_answer'] = $answer['is_correct'];

            if($answer['is_correct'] == 'true'){
                $has_correct_answer = true;
            }

            if($answer['id'] == 0){
                array_push($arr_new_answer,$item);
            }else{
                $item['id']=$answer['id'];
                array_push($arr_update_answer,$item);
            }

            $item = [];
        }

        if(!$has_correct_answer){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Please select the correct answer')]);
        }

        DB::beginTransaction();
        try{
            if($question->save()){
                if(count($arr_new_answer) > 0){
                    CCAnswer::insert($arr_new_answer);
                }
                if(count($arr_update_answer) > 0){
                    foreach ($arr_update_answer as &$item){
                        $id_answer = $item['id'];
                        unset($item['id']);
                        CCAnswer::where('id','=',$id_answer)->update($item);
                    }
                }
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return json_encode(['status'=> false, 'message'=> $this->single_message($e->getMessage())]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete_question(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $question = CCQuestion::find($request->id);

        if(!$question){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Data not found')]);
        }

        $question->row_status = 'deleted';

        if(!$question->save()){
            return json_encode(['status'=> false, 'message'=> $this->single_message('Server Error')]);
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete_answer(Request $request){
        $answer = CCAnswer::where('id','=',$request->id)->first();

        if($answer){
            $answer->row_status = 'deleted';

            if(!$answer->save()){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
            }
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function delete_prize(Request $request){
        $prize = CCSessionPrize::where('id','=',$request->id)->first();

        if($prize){
            $prize->row_status = 'deleted';
            $prize->updated_at = date('Y-m-d H:i');
            $prize->updated_by = Auth::user()->name;

            if(!$prize->save()){
                return json_encode(['status'=> false, 'message'=> $this->single_message('Something wrong.')]);
            }
        }

        return json_encode(['status'=> true, 'message'=> "Success"]);
    }

    public function get_question(Request $request){
        $question = CCQuestion::with('answer')->where('id','=',$request->id)->first();

        return json_encode(['status'=> true, 'data'=> $question]);
    }

    public function question_paging(Request $request){
        $query = CCQuestion::where('row_status','!=','deleted')->withCount('answer');

        return $this->data_table($query);
    }

    public function random_paging(Request $request){
        $count = $request->count ? $request->count : 20;
        $query = CCQuestion::inRandomOrder()->where('row_status','!=','deleted')
            ->withCount('answer')
            ->with('correct_answer')
            ->take($count);

        return $this->data_table($query);
    }

    public function session_paging(Request $request){
        $query = CCSession::where('row_status','!=','deleted')
            ->orderBy('open_date', 'DESC')
            ->withCount('question')
            ->withCount('participant')
            ->withCount('prize');

        return $this->data_table($query);
    }

    public function report(Request $request){
        $cc = CCSession::where('id','=',$request->id)->first();
        $data = [
            'cc' => $cc
        ];

        return view('admin.cerdas-cermat.report',$data);
    }
}