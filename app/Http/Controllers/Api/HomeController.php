<?php

namespace App\Http\Controllers\Api;

use App\Banner;
use App\Category;
use App\DynamicSection;
use App\FlashEvent;
use App\Helpers\Utils;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DynamicSectionResource;
use App\Http\Resources\FlashEventResource;
use App\News;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends ApiController
{
    /**
     * @OA\Get(
     * path="/api/home",
     * summary="Fetching data home",
     * description="Fetching data",
     * operationId="fetchingdata",
     * tags={"home"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="string", example="000"),
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="object", example="")
     *        )
     *     )
     * )
     */
    public function index(Request $request){
        $user = $this->user;
        $today = Carbon::now();
        $banner = Banner::where('row_status','=','active')->get();

        $category = Category::where('row_status','=','active')->get();

        $unfinished = [];

        $query_flash_event = FlashEvent::where('row_status','=','active')->with('detail');
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

        $dynamic_section = DynamicSection::where('row_status','=','active')->get();
        $news = News::select('id_news','title','url_to_image','reward')->where('row_status','=','active')->take(5)->get();
        $response = [
            'banner' => BannerResource::collection($banner),
            'category' => CategoryResource::collection($category),
            'unfinished' => $unfinished,
            'flash_event' => FlashEventResource::collection($arr_flash),
            'dynamic_section'=> DynamicSectionResource::collection($dynamic_section),
            'news' => $news,
            'user'=>$user
        ];

        return $this->successResponse($response);
    }

}
