<?php

namespace App\Http\Controllers\Api;

use App\FlashEvent;
use App\Http\Resources\FlashEventResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlashEventController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/flash-event/detail",
     *   summary="get detail of flash event by event_code",
     *   tags={"flash-event"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="event_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="A list with flash-event"
     *   )
     * )
     */
    public function get_flash_event(Request $request){
        $user = $this->user;
        $today = Carbon::now();
        $validation = Validator::make($request->all(), [
            'event_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $query_flash_event = FlashEvent::where('row_status','=','active')->with('detail')->where('event_code','=',$request->event_code);
        if($user->is_tester){
            $query_flash_event->where('is_tester','=',true);
        }else{
            $query_flash_event->where('is_tester','=',false);
        }
        $arr_flash_event = $query_flash_event->get();
        $arr_flash = [];
        foreach ($arr_flash_event as $item){
            if($item->ut_by_register_date){
                $register = Carbon::parse(date_format(date_create($user->register),"Y-m-d"));
                $from = Carbon::parse(date_format(date_create($item->registered_from),"Y-m-d"));
                $to = Carbon::parse(date_format(date_create($item->registered_to),"Y-m-d"));
                if(!($register->gte($from) && $register->lte($to))){
                    continue;
                }
            }elseif ($item->ut_by_point_count){
                $point = $user->total_earn - $user->total_use;
                if(!($point >= $item->target_point_from && $point <= $item->target_point_to)){
                    continue;
                }
            }

            if($item->event_period == 'weekly'){
                $date = date('Y-m-d');
                $d = new \DateTime($date);
                $day_name = $d->format('l');
                if($day_name == $item->day_name){
                    if(!($today->gte($item->event_start) && $today->lte($item->event_end))){
                        continue;
                    }
                }else{
                    continue;
                }
            }else{
                if(!($today->gte($item->event_start) && $today->lte($item->event_end))){
                    continue;
                }
            }

            array_push($arr_flash, $item);
        }

        if(!$arr_flash){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
        }

        $response = [
            'flash_event' => FlashEventResource::collection($arr_flash)
        ];

        return $this->successResponse($response);
    }
}
