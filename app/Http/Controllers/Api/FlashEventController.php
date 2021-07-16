<?php

namespace App\Http\Controllers\Api;

use App\FlashEvent;
use App\FlashEventDetail;
use App\Helpers\Code;
use App\Helpers\Pulsa;
use App\Helpers\User;
use App\Helpers\Utils;
use App\Http\Resources\FlashEventDetailResource;
use App\Http\Resources\FlashEventResource;
use App\PointPurchase;
use App\PulsaBuy;
use App\PulsaGoods;
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
        $validation = Validator::make($request->all(), [
            'event_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $arr_flash = $this->map_flash_event($request->event_code);
        if(!$arr_flash){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
        }

        $response = [
            'flash_event' => FlashEventResource::collection($arr_flash)
        ];

        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     *   path="/api/flash-event/detail/product",
     *   summary="get product by flash event detail code",
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
     *          name="flash_detail_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Product details"
     *   )
     * )
     */
    public function get_flash_event_product(Request $request){
        $product = FlashEventDetail::where('flash_detail_code','=', $request->flash_detail_code)->get();

        if(!$product){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
        }

        $stock = PulsaBuy::where('flash_detail_code','=',$request->flash_detail_code)->where('dt','=',date('Y-m-d'))->count();

        foreach ($product as &$item){
            $item['stock'] = $stock;
        }

        $response = [
            'flash_event' => FlashEventDetailResource::collection($product)
        ];

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     *   path="/api/flash-event/detail/product/buy",
     *   summary="buy the product by flash event detail code",
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
     *          name="flash_detail_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Transaction success"
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Flash event not found"
     *   ),
     *   @OA\Response(
     *     response=222,
     *     description="Flash event expired"
     *   ),
     *   @OA\Response(
     *     response=223,
     *     description="Out of stock"
     *   ),
     *   @OA\Response(
     *     response=224,
     *     description="Please verify the phone number"
     *   ),
     *   @OA\Response(
     *     response=225,
     *     description="Purchase can only be made once, 1 user 1 transaction"
     *   ),
     *   @OA\Response(
     *     response=226,
     *     description="Product not found"
     *   ),
     *   @OA\Response(
     *     response=216,
     *     description="insufficient point"
     *   )
     * )
     */
    public function buy_product(Request $request){

        $user = $this->user;

        $exist  = PulsaBuy::where('flash_detail_code','=',$request->flash_detail_code)
            ->where('uid','=',$user->uid)
            ->where('dt', date('Y-m-d'))->count();
        if($exist > 0){
            return $this->errorResponse(static::ERROR_FLASH_BUY_DUPLICATE,static::ERROR_CODE_FLASH_BUY_DUPLICATE);
        }

        $point_purchase = PointPurchase::where('transaction_code','=',$request->flash_detail_code)->where('uid','=',$user->uid)->count();
        if($point_purchase > 0){
            return $this->errorResponse(static::ERROR_FLASH_BUY_DUPLICATE,static::ERROR_CODE_FLASH_BUY_DUPLICATE);
        }

        $rand = rand(1000000,2000000);
        usleep($rand);


        $flash_detail = FlashEventDetail::with('flash_event')->with('product')->where('flash_detail_code','=', $request->flash_detail_code)->first();
        $flash_event_id = $flash_detail->flash_event_id;

        $query_flash_event = FlashEvent::where('id','=',$flash_event_id)->first();

        $now = date('Y-m-d H:i:s');
        if($query_flash_event->event_period == 'daily'){
            $date_from = date('Y-m-d');
            $date_to = date('Y-m-d');
        }else{
            $date_from = $query_flash_event->date_from;
            $date_to= $query_flash_event->date_to;
        }

        $start_date_time = $date_from.' '.$query_flash_event->time_from;
        $end_date_time = $date_to.' '.$query_flash_event->time_to;


        if($now < $start_date_time){
            return $this->errorResponse(static::ERROR_FLASH_BUY_NOT_STARTED,static::ERROR_CODE_FLASH_EVENT_NOT_STARTED);
        }else if($now > $end_date_time){
            return $this->errorResponse(static::ERROR_FLASH_EVENT_EXPIRED,static::ERROR_CODE_FLASH_EVENT_EXPIRED);
        }

        if(!$flash_detail){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
        }

        $point = $user->total_earn - $user->total_use;
        if($flash_detail->point > $point){
            return $this->errorResponse(static::ERROR_INSUFFICIENT_POINT,static::ERROR_CODE_INSUFFICIENT);
        }

        if(count(self::map_flash_event($flash_detail->flash_event->event_code)) == 0){
            return $this->errorResponse(static::ERROR_FLASH_EVENT_EXPIRED,static::ERROR_CODE_FLASH_EVENT_EXPIRED);
        }

        if (!$user->phone) {
            return $this->errorResponse(static::ERROR_NEED_PHONE,static::ERROR_CODE_PHONE_NUMBER);
        }

        $status = false;

        if($flash_detail->product->product_type == 'point'){
            User::earn_point($user, Code::CODE_BONUS, $flash_detail->product->product_value, 'Flash Event');

            $data_point_purchase = PointPurchase::create(
                ([
                    'uid' => $user->uid,
                    'transaction_code' => $request->flash_detail_code,
                    'description' => 'Flash Event - '.$flash_detail->flash_event->event_name,
                    'price' => $flash_detail->point
                ])
            );
            User::use_cash($user,Code::USING_PAY_POINT, $flash_detail->point, null, 'point_'.$data_point_purchase->id);
        }else{
            $stock = PulsaBuy::where('flash_detail_code','=',$request->flash_detail_code)->where('dt','=',date('Y-m-d'))->count();

            if($flash_detail->cap <= $stock){
                return $this->errorResponse(static::ERROR_FLASH_EVENT_OUT_OF_STOCK,static::ERROR_CODE_FLASH_EVENT_OUT_OF_STOCK);
            }

            $pulsa_goods = PulsaGoods::where('opcode','=',$user->opcode)->where('good_code','=',$flash_detail->product->product_code)->where('server_pulsa','=','MOBILEPULSA')->first();



            $trans = PulsaBuy::create(
                ([
                    'uid' => $user->uid,
                    'pulsa_goods_id' => $pulsa_goods->id,
                    'cash' => $flash_detail->point,
                    'phone' => $user->phone,
                    'flash_detail_code' => $request->flash_detail_code,
                    'additional_1' => $request->flash_detail_code
                ])
            );

            if($trans){
                $status = true;
                User::use_cash($user,Code::USING_PAY_PULSA, $flash_detail->point, null, 'pulsa_'.$trans->id);
            }else{

                $status = false;
                return $this->errorResponse(static::ERROR_FLASH_EVENT_OUT_OF_STOCK,static::ERROR_CODE_FLASH_EVENT_OUT_OF_STOCK);
            }

        }

        $response = [
            'status' => $status
        ];

        return $this->successResponse($response);
    }

    public function map_flash_event($event_code){
        $user = $this->user;
        $today = Carbon::now();

        $query_flash_event = FlashEvent::where('row_status','=','active')->with('detail')->where('event_code','=',$event_code);
        
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
                $d = new \DateTime($date , new \DateTimeZone('Asia/jakarta'));
                $day_name = $d->format('l');
                if($day_name == $item->day_name){
                    if(!($today->gte(Utils::gmt_plus_seven($item->event_start)) && $today->lte(Utils::gmt_plus_seven($item->event_end)))){
                        continue;
                    }
                }else{
                    continue;
                }
            }else{
                if(!($today->gte(Utils::gmt_plus_seven($item->event_start)) && $today->lte(Utils::gmt_plus_seven($item->event_end)))){
                    echo 'a';
                    continue;
                }
            }

            array_push($arr_flash, $item);
        }

        return $arr_flash;
    }

    
}
