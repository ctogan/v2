<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Code;
use App\Helpers\User;
use App\Http\Resources\NewsDetailResource;
use App\Http\Resources\NewsResource;
use App\News;
use App\NewsRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class NewsController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/news",
     *   summary="list news",
     *   tags={"news"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="A list with news"
     *   )
     * )
     */
    public function index(Request $request){
        $page = $request->page;
        $latest = null;

        if($page == 1){
            $latest = Cache::remember('__latest_news_list'.$page,3600, function (){
                return News::select('id','title','url_to_image','reward','news_code')
                    ->withCount('news_read')
                    ->where('row_status','=','active')
                    ->orderBy('id','DESC')
                    ->take(5)
                    ->get();
            });
        }
        $news = Cache::remember('__news_list5'.$page,3600, function (){
            return News::select('id','title','url_to_image','reward','news_code')
                ->withCount('news_read')
                ->where('row_status','=','active')
                ->orderBy('id','DESC')
                ->paginate(5);
        });

        $response = [
            'news' => NewsResource::collection($news),
            'latest' => $latest ? NewsResource::collection($latest) : null,
        ];

        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     *   path="/api/news/detail",
     *   summary="get news detail by news_code",
     *   tags={"news"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="news_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="detail of news"
     *   )
     * )
     */
    public function get(Request $request){
        $user = $this->user;
        $validation = Validator::make($request->all(), [
            'news_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $code = $request->news_code;
        $uid = $user->uid;
        Cache::forget('__news_detail5'.$code);
        $news = Cache::remember('__news_detail5'.$code, 3600, function () use ($code, $uid){
            $objNews = News::where('news_code','=',$code)->first();
            if($objNews){
                $objNewsRead = NewsRead::where('uid','=',$uid)->where('id_news','=',$objNews->id)->first();
                if($objNewsRead){
                    $objNews->reward = 0;
                }
            }

            return $objNews;
        });

        if(!$news){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::ERROR_CODE_NOT_FOUND);
        }

        $recommendation = Cache::remember('__recommendation_news_list',3600, function (){
            return News::select('id','title','url_to_image','reward','news_code')
                ->withCount('news_read')
                ->where('row_status','=','active')
                ->orderBy('id','DESC')
                ->inRandomOrder()
                ->limit(5)
                ->get();
        });

        $response = [
            'news' => NewsDetailResource::collection(array($news)),
            'recommendation' => NewsResource::collection($recommendation)
        ];

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     *   path="/api/news/point",
     *   summary="give reward after read news",
     *   tags={"news"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="news_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="news detail"
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="validation error, session code not exist"
     *   ),
     *   @OA\Response(
     *     response=215,
     *     description="news not found"
     *   )
     * )
     */
    public function point(Request $request){
        $user = $this->user;
        $validation = Validator::make($request->all(), [
            'news_code' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $news_code = $request->news_code;
        $news = Cache::remember('__news_'.$news_code,3600, function () use ($news_code){
            return News::where('row_status','=','active')
                ->where('news_code','=',$news_code)
                ->first();
        });
        

        if(!$news){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::ERROR_CODE_NOT_FOUND);
        }

        $news_id = $news->id;
        $uid = $user->uid;
        $news_read = Cache::rememberForever('__news_point_'.$news_code.$user->uid, function () use ($news_id, $uid){
            return NewsRead::where('id_news','=',$news_id)
                ->where('uid','=',$uid)->first();
        });

        
        if(!$news_read){
            $arr_insert = [
                'id_news' => $news->id,
                'uid' => $user->uid,
                'reward' => $news->reward,
                'created_at' => date('Y-m-d h:m:s')
            ];
            $news->reward = $news->reward;
            NewsRead::insert($arr_insert);

            User::earn_point($user, Code::CODE_BONUS, $news->reward,'News : '.substr($news->title,0,20)."..." );
        }else{
            $news->reward = 0;
        }

        $response = [
            'news' => $news
        ];

        return $this->successResponse($response);
    }
}