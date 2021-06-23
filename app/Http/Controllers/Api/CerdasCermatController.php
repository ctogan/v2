<?php

namespace App\Http\Controllers\Api;

use App\CCAnswer;
use App\CCParticipant;
use App\CCParticipantDetail;
use App\CCQuestion;
use App\CCSession;
use App\CCSessionPrize;
use App\CCSessionQuestion;
use App\Helpers\Code;
use App\Helpers\User;
use App\Http\Resources\CCSessionResource;
use App\PointPurchase;
use App\PulsaBuy;
use App\PulsaGoods;
use App\UserApp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CerdasCermatController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/cerdas-cermat",
     *   summary="list of cerdas cermat",
     *   tags={"cerdas-cermat"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="A list with cerdas cermat"
     *   )
     * )
     */
    public function index(Request $request){
        $uid = $this->user->uid;

        $session = CCSession::where('row_status','=','active')
            ->where('open_date', '>=', date('Y-m-d'))
            ->with(['participant' => function ($q) use ($uid){
                $q->where('uid' , $uid);
            }])
            ->with('prize')
            ->get();

        $response['session'] = CCSessionResource::collection($session);

        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     *   path="/api/cerdas-cermat/detail",
     *   summary="get cerdas cermat by session_code",
     *   tags={"cerdas-cermat"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="session_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="A list with cerdas cermat"
     *   )
     * )
     */
    public function get(Request $request){
        $uid = $this->user->uid;
        $validation = Validator::make($request->all(), [
            'session_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $session = CCSession::where('row_status','=','active')
            ->where('open_date', '>=', date('Y-m-d'))
            ->withCount(['participant' => function ($q) use ($uid){
                $q->where('uid' , $uid);
            }])
            ->with('prize')
            ->get();

        if(!$session){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::ERROR_CODE_NOT_FOUND);
        }

        $response['session'] = CCSessionResource::collection($session);

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     *   path="/api/cerdas-cermat/register",
     *   summary="user register to join cerdas cermat session",
     *   tags={"cerdas-cermat"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="session_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="registration successfully"
     *   ),
     *   @OA\Response(
     *     response=216,
     *     description="insufficient point"
     *   ),
     *   @OA\Response(
     *     response=217,
     *     description="already registered"
     *   ),
     *   @OA\Response(
     *     response=218,
     *     description="session expired"
     *   )
     * )
     */
    public function register(Request $request){
        $user = $this->user;
        $today = Carbon::now();
        $validation = Validator::make($request->all(), [
            'session_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $session = CCSession::where('session_code','=',$request->session_code)->first();
        if(!$session){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::ERROR_CODE_NOT_FOUND);
        }

        $participant = CCParticipant::where('cc_session_id','=',$session->id)->where('uid','=',$user->uid)->first();
        if($participant){
            return $this->errorResponse(static::ERROR_CC_ALREADY_REGISTERED,static::ERROR_CODE_CC_ALREADY_REGISTERED);
        }

        $point = $user->total_earn - $user->total_use;
        if($session->registration_fee > $point){
            return $this->errorResponse(static::ERROR_INSUFFICIENT_POINT,static::ERROR_CODE_INSUFFICIENT);
        }

        $start = Carbon::parse(date_format(date_create($session->open_date .' '. $session->time_end),"Y-m-d H:i"));
        if($today->gte($start)){
            return $this->errorResponse(static::ERROR_CC_REGISTRATION_CLOSED,static::ERROR_CODE_CC_REGISTRATION_CLOSED);
        }

        $data = [
            'row_status'=>'pending',
            'cc_session_id'=>$session->id,
            'uid' => $user->uid,
            'last_point' => $point,
            'cc_register_date' => date('Y-m-d h:m:s'),
            'app_register_date' => date_format(date_create($user->register), "Y-m-d H:i"),
            'score' => 0
        ];

        CCParticipant::insert($data);
        $response['session'] = CCSessionResource::collection(array($session));

        Cache::forget('_list_session_'.$user->uid);

        User::use_cash($user,Code::USING_PAY_CCC, $session->registration_fee, 'Registration Cerdas Cermat');

        return $this->successResponse($response);
    }

    public function free_question(Request $request){
        $question = CCQuestion::inRandomOrder()->where('row_status','=','active')
            ->with('answer')
            ->take(1)
            ->get();

        $data = [
            'mmses'=>$request->mmses,
            'question' => $question
        ];

        return $this->successResponse($data);
    }

    public function submit_free_trial(Request $request){
        $validation = Validator::make($request->all(), [
            'item' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $score =0;
        foreach ($request->item as $item){
            if(!isset($item['answer'])){
                continue;
            }
            $answer = CCAnswer::where('cc_question_id','=',$item['question'])
                ->where('id','=',$item['answer'])
                ->first();

            if($answer){
                if($answer->is_correct_answer){
                    $score+=1;
                }
            }
        }

        $data = [
            'correct' => $score,
            'wrong' => 10 - $score
        ];

        return $this->successResponse($data);
    }

    public function start(Request $request){
        $user = $this->user;
        $today = Carbon::now();
        $validation = Validator::make($request->all(), [
            'session_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $session = CCSession::where('session_code','=',$request->session_code)->first();
        if(!$session){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::ERROR_CODE_NOT_FOUND);
        }

        $participant = CCParticipant::where('cc_session_id','=',$session->id)->where('uid','=',$user->uid)->first();
        if(!$participant){
            return $this->errorResponse(static::ERROR_CC_NOT_REGISTERED,static::ERROR_CODE_CC_NOT_REGISTRERED);
        }

        if($participant->row_status == 'completed'){
            return $this->errorResponse(static::ERROR_CC_WAITING_WINNER,static::ERROR_CODE_CC_WAITING_WINNER);
        }

        $start = Carbon::parse(date_format(date_create($session->open_date .' '. $session->time_end),"Y-m-d H:i"));
        if($today->gte($start)){
            return $this->errorResponse(static::ERROR_CC_SESSION_ENDED, static::ERROR_CODE_CC_SESSION_ENDED);
        }

        $minute = 0;
        $second = 0;
        $milisecond =0;
        $page =0;
        if($participant->row_status == 'pending'){
            $participant->time_start = date('Y-m-d h:m:s');
            $participant->row_status = 'active';
            $participant->save();
        }else{
            $detail = CCParticipantDetail::where('cc_participant_id','=',$participant->id)->count();
            $page = $detail+1;
            $duration = $participant->duration;
            if($duration != null){
                $arr = explode(':', $duration);
                $minute = (int)$arr[0];
                $second = (int)$arr[1];
                $milisecond = (int)$arr[2];
            }
        }

        $data = [
            'mmses'=>$request->mmses,
            'session' =>$session,
            'page' => $page,
            'minute' => $minute,
            'second' => $second,
            'milisecond' => $milisecond
        ];

        return $this->successResponse($data);
    }

    public function get_question(Request $request){
        $uid = $this->user->uid;
        $session = CCSession::where('row_status','=','active')
            ->where('open_date', '>=', date('Y-m-d'))
            ->where('session_code','=',$request->session_code)
            ->first();

        $key = '_list_question_'.$uid.$session->session_code;
        $question  = Cache::get($key);
        if(!$question){
            $question = CCSessionQuestion::inRandomOrder()
                ->with('question')
                ->with('question.answer')
                ->take($session->displayed_question)
                ->where('cc_session_id','=',$session->id)
                ->get();

            Cache::put($key, $question);
        }

        $q = [];
        foreach ($question as $item){
            array_push($q, $item->question);
        }

        if($request->question_id > 0){
            $participant = CCParticipant::where('cc_session_id','=',$session->id)->where('uid','=',$uid)->first();
            $answer_id = 0;
            $score = 0;
            if($request->answer_id > 0){
                $answer = CCAnswer::where('cc_question_id','=', $request->question_id)
                    ->where('id','=', $request->answer_id)
                    ->first();

                if($answer){
                    $answer_id = $answer->id;
                    if($answer->is_correct_answer){
                        $score=1;
                    }
                }
            }

            $data = [
                'cc_participant_id' =>$participant->id,
                'question_id' => $request->question_id,
                'answer_id' => $answer_id,
                'score' => $score,
                'answer_date' => date('Y-m-d h:m:s')
            ];

            CCParticipantDetail::insert($data);
            $participant->score = $participant->score + $score;
            $participant->time_end = date('Y-m-d h:m:s');
            $participant->duration = $request->minute .':'.$request->second .':' .$request->milisecond;

            $participant->save();
        }

        $data = [
            'key'=>$key,
            'mmses'=>$request->mmses,
            'session' =>$session,
            'question' => $q[$request->page]
        ];

        return $this->successResponse($data);
    }

    public function submit(Request $request){
        $uid = $this->user->uid;

        $validation = Validator::make($request->all(), [
            'session_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $session = CCSession::where('row_status','=','active')
            ->where('open_date', '>=', date('Y-m-d'))
            ->where('session_code','=',$request->session_code)
            ->first();

        $participant = CCParticipant::where('cc_session_id','=',$session->id)->where('uid','=',$uid)->first();
        $answer_id = 0;
        $score = 0;
        if($request->answer_id > 0){
            $answer = CCAnswer::where('cc_question_id','=', $request->question_id)
                ->where('id','=', $request->answer_id)
                ->first();

            if($answer){
                $answer_id = $answer->id;
                if($answer->is_correct_answer){
                    $score=1;
                }
            }
        }

        $data = [
            'cc_participant_id' =>$participant->id,
            'question_id' => $request->question_id,
            'answer_id' => $answer_id,
            'score' => $score,
            'answer_date' => date('Y-m-d h:m:s')
        ];

        CCParticipantDetail::insert($data);
        $participant->score = $participant->score + $score;
        $participant->time_end = date('Y-m-d h:m:s');
        $participant->duration = $request->minute .':'.$request->second .':' .$request->milisecond;
        $participant->minutes = $request->minute;
        $participant->seconds = $request->second;
        $participant->miliseconds = $request->milisecond;
        $participant->row_status = 'completed';
        $participant->save();

        $data = [
            'correct' => $participant->score,
            'wrong' => $session->displayed_question - $participant->score,
            'duration' => $request->duration
        ];

        $key = '_list_question_'.$uid.$session->session_code;
        Cache::forget($key);

        return $this->successResponse($data);
    }

    public function history(Request $request){
        $uid = $this->user->uid;

        $session = CCSession::where('cc_session.row_status','=','active')
            ->join('cc_participant','cc_participant.cc_session_id','=','cc_session.id')
            ->with(['participant' => function ($q) use ($uid){
                $q->where('uid' , $uid);
            }])
            ->with('prize')
            ->where('cc_participant.uid','=',$uid)
            ->select('cc_session.*')
            ->get();

        $response['session'] = CCSessionResource::collection($session);

        return $this->successResponse($response);
    }

    public function result(Request $request){
        $uid = $this->user->uid;

        $validation = Validator::make($request->all(), [
            'session_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $session = CCSession::where('row_status','=','active')
            ->where('session_code','=',$request->session_code)
            ->first();

        $query = "WITH rank as (
              select uid, score, duration,
              RANK () OVER (
                    ORDER BY score DESC , minutes ASC, seconds ASC, miliseconds asc, last_point desc , cc_register_date DESC
                ) rank
              from cc_participant where row_status='completed' and cc_session_id=".$session->id." limit 15
            )
            select * from rank ";

        $result = DB::connection('game_center')->select($query);
        $myrank = DB::connection('game_center')->select($query . ' where uid=' .$uid);

        foreach ($result as &$item){
            $userapp = UserApp::where('uid','=',$item->uid)->first();
            $phone = $userapp && $userapp->phone ? substr($userapp->phone,0,strlen($userapp->phone)-3) . '***' : '' ;
            $item->name = $userapp ? $userapp->first_name : '-';
            $item->phone = $phone;
        }

        $rank = 0;
        if($myrank){
            foreach ($myrank as $my){
                $rank =$my->rank;
            }
        }

        $response =[
            'result' => $result,
            'uid' => $uid,
            'rank' => $rank
        ];

        return $this->successResponse($response);
    }

    public function get_prize(Request $request){
        sleep(1);

        $user = $this->user;
        $uid = $this->user->uid;

        $validation = Validator::make($request->all(), [
            'session_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $session = CCSession::where('row_status','=','active')
            ->where('session_code','=',$request->session_code)
            ->first();

        if(!$session){
            return $this->errorResponse('Sesi ini tidak ditemukan.',static::CODE_ERROR_VALIDATION);
        }

        $query = "WITH rank as (
              select uid, score, duration,
              RANK () OVER (
                    ORDER BY score DESC , minutes ASC, seconds ASC, miliseconds asc, last_point desc , cc_register_date DESC
                ) rank
              from cc_participant where row_status='completed' and cc_session_id=".$session->id." limit 15
            )
            select * from rank ";

        $myrank = DB::connection('game_center')->select($query . ' where uid=' .$uid);

        $rank = 0;
        if($myrank){
            foreach ($myrank as $my){
                $rank =$my->rank;
            }
        }

        if($rank > 0){
            $prize = CCSessionPrize::with('product')
                ->where('cc_session_id','=',$session->id)
                ->where('rank','=',$rank)
                ->first();

            if(!$prize){
                return $this->errorResponse('Tidak ada hadiah yang tersedia',static::CODE_ERROR_VALIDATION);
            }

            if($prize->product->product_type == 'point'){
                $point_purchase = PointPurchase::where('transaction_code','=',$request->session_code)->where('uid','=',$user->uid)->count();
                if($point_purchase > 0){
                    return $this->errorResponse('Hadiah sudah pernah diambil',static::CODE_ERROR_VALIDATION);
                }

                User::earn_point($user, Code::CODE_BONUS, $prize->product->product_value, 'Cerdas Cermat');
                $data_point_purchase = [
                    'uid' => $user->uid,
                    'transaction_code' => $request->session_code,
                    'description' => 'Cerdas Cermat - '.$session->title,
                    'price' => 0
                ];

                PointPurchase::insert($data_point_purchase);
            }else{
                $additional_1 = $request->session_code .'_' . $uid;
                $exist = PulsaBuy::where('additional_1','=', $additional_1)->count();
                if($exist > 0){
                    return $this->errorResponse('Hadiah sudah pernah diambil',static::CODE_ERROR_VALIDATION);
                }

                $pulsa_goods = PulsaGoods::where('opcode','=',$user->opcode)->where('good_code','=',$prize->product->product_code)->first();
                if(!$pulsa_goods){
                    return $this->errorResponse(static::ERROR_PRODUCT_NOT_FOUND,static::CODE_ERROR_VALIDATION);
                }

                $pulsa_buy = [
                    'uid' => $user->uid,
                    'pulsa_goods_id' => $pulsa_goods->goods_id,
                    'cash' => 0,
                    'phone' => $user->phone,
                    'additional_1' => $additional_1
                ];

                PulsaBuy::create($pulsa_buy);
            }
        }else{
            return $this->errorResponse('Tidak ada hadiah yang tersedia',static::CODE_ERROR_VALIDATION);
        }

        $response = [
            'status' => true,
            'uid' => $uid,
            'rank' => $rank,
            'prize' => $prize->product->product_name
        ];

        return $this->successResponse($response);
    }
}
