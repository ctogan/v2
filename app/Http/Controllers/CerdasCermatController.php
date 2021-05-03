<?php

namespace App\Http\Controllers;

use App\CCAnswer;
use App\CCQuestion;
use App\Helpers\Utils;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function question_list(Request $request){
        return view('admin.cerdas-cermat.question.index');
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

        $this->forget_cache_tag('__question');
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

        $this->forget_cache_tag('__question');
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

    public function get_question(Request $request){
        $question = CCQuestion::with('answer')->where('id','=',$request->id)->first();

        return json_encode(['status'=> true, 'data'=> $question]);
    }

    public function question_paging(Request $request){
        $query = CCQuestion::where('row_status','!=','deleted')->withCount('answer');

        return $this->data_table($query);
    }

    public function random_paging(Request $request){
        $query = CCQuestion::inRandomOrder()->where('row_status','!=','deleted')->withCount('answer');

        return $this->data_table($query);
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
}