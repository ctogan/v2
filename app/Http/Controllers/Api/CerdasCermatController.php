<?php

namespace App\Http\Controllers\Api;

use App\CCAnswer;
use App\CCParticipant;
use App\CCQuestion;
use App\CCSession;
use App\CCSessionQuestion;
use App\Helpers\Utils;
use App\Http\Resources\CCSessionResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $session = Cache::remember('_list_session_'.$uid, 3600, function () use ($uid){
            return CCSession::where('row_status','=','active')
                ->where('open_date', '>=', date('Y-m-d'))
                ->withCount(['participant' => function ($q) use ($uid){
                    $q->where('uid' , $uid);
                }])
                ->with('prize')
                ->get();
        });

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

        $point = $user->total_use - $user->total_earn;
        if($session->point > $point){
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

        $data = [
            'key'=>$key,
            'mmses'=>$request->mmses,
            'session' =>$session,
//            'question' => $question[$request->page]
            'question' => $q[$request->page]
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

    public function submit(Request $request){
        $user = $this->user;

        $validation = Validator::make($request->all(), [
            'item' => 'required',
            'session_code' => 'required'
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
            'wrong' => 10 - $score,
            'duration' => $request->duration
        ];

        Cache::forget('_list_session_'.$user->uid);

        return $this->successResponse($data);
    }
}
