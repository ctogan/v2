<?php

namespace App\Http\Controllers\Api;

use App\Banner;
use App\Category;
use App\DynamicSection;
use App\FlashEvent;
use App\Helpers\Utils;
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
        $today = Carbon::today();
        $now = date("Y-m-d H:i:s");

        $banner = Banner::where('row_status','=','active')->get();

        $category = Category::where('row_status','=','active')->get();

        $unfinished = [];

        $flash_event = FlashEvent::where('row_status','=','active')->get();

        $dynamic_section = DynamicSection::where('row_status','=','active')->get();

        $news = News::select('id_news','title','url_to_image','reward')->where('row_status','=','active')->take(5)->get();

        $response = [
            'banner' => $banner,
            'category' => $category,
            'unfinished' => $unfinished,
            'flash_event' => $flash_event,
            'dynamic_section'=> $dynamic_section,
            'news' => $news
//            'end_flash_sale' => isset( $flash_sale[0]->promotion_end_date ) ? $flash_sale[0]->promotion_end_date : "" ,
//            'flash_sale' => $flash_sale,
//            'hot_issue' => $hot_issue,
//            'special_discount' => $special_discount,
//            'config' => $config
        ];

        return $this->successResponse($response);
    }

}
