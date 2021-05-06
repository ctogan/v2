<?php

namespace App\Http\Controllers\Api;

use App\CCSession;
use App\Http\Resources\CCSessionResource;
use Illuminate\Http\Request;

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
            ->withCount(['participant' => function ($q) use ($uid){
                $q->where('uid' , $uid);
            }])
            ->with('prize')
            ->get();

        $response['session'] = CCSessionResource::collection($session);

        return $this->successResponse($response);
    }
}
