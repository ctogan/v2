<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsDetailResource;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends ApiController
{
    /**
     * @OA\Get(
     * path="/api/search/recommendation",
     * summary="Fetching data recommendation",
     * description="Fetching data",
     * operationId="fetchingdata",
     * tags={"search"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
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
        $news = Cache::remember('__search_recommendation',3600,function (){
            return News::where('is_recommendation',true)->take(4)
                ->orderBy('id','DESC')
                ->get();
        });

        $response = [
            'recommendation' => NewsDetailResource::collection($news)
        ];

        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     * path="/api/search",
     * summary="Fetching data based on keyword",
     * tags={"search"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="keyword",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="list of result data",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="string", example="000"),
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="object", example="")
     *        )
     *     )
     * )
     */
    public function search(Request $request){
        $result = News::where('title','ilike','%'.$request->keyword.'%')
            ->orderBy('id','DESC')
            ->take(5)->paginate();

        $response = [
            'news' => NewsDetailResource::collection($result),
            'campaign' => null
        ];

        return $this->successResponse($response);
    }
}