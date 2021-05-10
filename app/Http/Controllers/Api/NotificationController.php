<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Notification;
use App\NotificationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/notification",
     *   summary="list of notification",
     *   tags={"notification"},
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
     *     description="A list of notifications"
     *   )
     * )
     */
    public function index(Request $request){
        $user = $this->user;
        $uid = $user->uid;

        $notification = Notification::
            with(['detail'=>function($q) use($uid){
                return $q->where('uid','=', $uid);
            }])->paginate();

        $response = [
            'notification' => NotificationResource::collection($notification)
        ];

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     *   path="/api/notification/read",
     *   summary="list of notification",
     *   tags={"notification"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="notification_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="success message"
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Notification ID Mandatory"
     *   )
     * )
     */
    public function read(Request $request){
        $validation = Validator::make($request->all(), [
            'notification_id' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $user = $this->user;
        $uid = $user->uid;

        $detail = NotificationDetail::where('notification_id','=',$request->notification_id)
            ->where('uid','=',$uid)->first();

        if($detail){
            if(!$detail->is_read){
                $detail->is_read = true;
                $detail->updated_at =date('Y-m-d h:m:s');
                $detail->save();
            }
        }else{
            $data = [
                'notification_id' => $request->notification_id,
                'uid' => $uid,
                'is_read' => true,
                'created_at' => date('Y-m-d h:m:s')
            ];

            NotificationDetail::insert($data);
        }

        return $this->successResponse();
    }
}
