<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NewsDetailResource;
use App\Http\Resources\NewsResource;
use App\News;
use App\NewsRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class NewsController extends ApiController
{
    public function index(Request $request){
        $page = $request->page;

        $news = Cache::tags('news')->remember('__news_list2'.$page,3600, function (){
            return News::select('id','title','url_to_image','reward')
                ->withCount('news_read')
                ->where('row_status','=','active')
                ->orderBy('id','DESC')
                ->paginate();
        });

        $response = [
            'news' => NewsResource::collection($news)
        ];

        return $this->successResponse($response);
    }

    public function get_news(Request $request){
        $user = $this->user;
        $validation = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $id = $request->id;
        $uid = $user->uid;
        $news = Cache::tags('news')->remember('__news_detail'.$id, 3600, function () use ($id, $uid){
            $objNews = News::where('id','=',$id)->get();
            if(count($objNews) > 0){
                $objNewsRead = NewsRead::where('uid','=',$uid)->where('id_news','=',$id)->first();
                if($objNewsRead){
                    $objNews[0]['reward'] = 0;
                }
            }

            return $objNews;
        });

        if(!$news){
            return $this->errorResponse(static::ERROR_NOT_FOUND,static::CODE_ERROR_VALIDATION);
        }

        $response = [
          'news' => NewsDetailResource::collection($news)
        ];

        return $this->successResponse($response);
    }
}