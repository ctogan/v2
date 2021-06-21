<?php

namespace App\Http\Controllers\Api;

use App\FlashEvent;
use App\FlashEventDetail;
use App\Helpers\Code;
use App\Helpers\User;
use App\Http\Resources\FlashEventDetailResource;
use App\Http\Resources\FlashEventResource;
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

        $arr_flash = self::map_flash_event($request->event_code);
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

        $stock = PulsaBuy::where('flash_detail_code','=',$request->flash_detail_code)->count();

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
     *   )
     * )
     */
    public function buy_product(Request $request){
        $user = $this->user;
        $exist  = PulsaBuy::where('flash_detail_code','=',$request->flash_detail_code)->where('uid','=',$user->uid)->count();
        if($exist > 0){
            return $this->errorResponse(static::ERROR_FLASH_BUY_DUPLICATE,static::ERROR_CODE_FLASH_BUY_DUPLICATE);
        }

        $rand = rand(30,120);
        sleep($rand);

        $flash_detail = FlashEventDetail::with('flash_event')->with('product')->where('flash_detail_code','=', $request->flash_detail_code)->first();
        if(!$flash_detail){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
        }

        if(count(self::map_flash_event($flash_detail->flash_event->event_code)) == 0){
            return $this->errorResponse(static::ERROR_FLASH_EVENT_EXPIRED,static::ERROR_CODE_FLASH_EVENT_EXPIRED);
        }

        if (!$user->phone) {
            return $this->errorResponse(static::ERROR_NEED_PHONE,static::ERROR_CODE_PHONE_NUMBER);
        }

        $status = false;

        if($flash_detail->product->product_type == 'point'){
//            User::use_cash($user,Code::USING_PAY_PULSA, $flash_detail->point, null);
//            User::earn_point($user,Code::USING_PAY_PULSA, $flash_detail->point, null);
        }else{
            $stock = PulsaBuy::where('flash_detail_code','=',$request->flash_detail_code)->count();

            if($flash_detail->cap <= $stock){
                return $this->errorResponse(static::ERROR_FLASH_EVENT_OUT_OF_STOCK,static::ERROR_CODE_FLASH_EVENT_OUT_OF_STOCK);
            }

            $pulsa_goods = PulsaGoods::where('opcode','=',$user->opcode)->where('good_code','=',$flash_detail->product->product_code)->first();

            if(!$pulsa_goods){
                return $this->errorResponse(static::ERROR_PRODUCT_NOT_FOUND,static::ERROR_CODE_FLASH_EVENT_OUT_OF_STOCK);
            }

            $pulsa_buy = [
                'uid' => $user->uid,
                'pulsa_goods_id' => $pulsa_goods->goods_id,
                'cash' => $flash_detail->point,
                'phone' => $user->phone
            ];

            $trans = PulsaBuy::create($pulsa_buy);
            if($trans){
                $status = true;
                User::use_cash($user,Code::USING_PAY_PULSA, $flash_detail->point, null, 'pulsa_'.$trans->seq);
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

        return $arr_flash;
    }
}
