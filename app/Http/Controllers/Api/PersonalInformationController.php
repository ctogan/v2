<?php

namespace App\Http\Controllers\Api;

use App\BioEntryCode;
use App\BioEntryValue;
use App\CCSession;
use App\Helpers\Code;
use App\Helpers\User;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalInformationResource;
use App\PersonalInformation;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

class PersonalInformationController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/get/personal/information",
     *   summary="get user biodata",
     *   tags={"biodata"},
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
     *     description="get biodata per user"
     *   )
     * )
     */

    public function get(Request $request){


        $uid = $this->user->uid;

        $biodata = DB::connection('users')->select('
            select b.uid,a.code,a.code_name , c.value_name from bio_entry_code as a
            LEFT JOIN user_bio_entry as b on b.code = a.code AND b.uid = '.$uid.'
            LEFT JOIN bio_entry_value as c on c.value = b.value AND c.bio_entry_code_id = b.code ORDER BY a.code ASC;
            ');


        $response =[
            'personal_information'=> PersonalInformationResource::collection($biodata),
        ];


        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     *   path="/api/get/master/biodata",
     *   summary="get master biodata",
     *   tags={"biodata"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="get master biodata list"
     *   )
     * )
     */

    public function get_master(Request $request){

        $master = BioEntryValue::where('bio_entry_code_id',$request->code)->get();
        $response =[
            'list'=> $master ,
        ];
        return $this->successResponse($response);
    }


    /**
     * @OA\Post(
     *   path="/api/personal/biodata/update",
     *   summary="update personal biodata",
     *   tags={"biodata"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="value",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="update personal information successfully"
     *   ),
     * )
     */


    public function update(Request $request){


        $user = $this->user;
        $uid = $user->uid;
        $check_user = PersonalInformation::where('uid',$uid)->where('code',$request->code)->first();

        if($check_user){
            try{
                PersonalInformation::where([
                    ['uid',$uid],
                    ['code',$request->code]
                ])->update
                ([
                    "value" => $request->value,
                ]);

                DB::commit();
            }
            catch (Exception $e) {
                DB::rollback();
            }
        }else{
            $data = [
                'uid'=>$uid,
                'code'=> $request->code,
                'value' => $request->value,
                'register' =>  date('Y-m-d h:m:s'),
                'got_rwd' => date('Y-m-d h:m:s'),
            ];
            PersonalInformation::insert($data);

            User::earn_point($user, Code::CODE_BONUS,'100' ,'biodata reward'."..." );
        }

        $biodata = DB::connection('users')->select('select b.uid,a.code,a.code_name , c.value_name from bio_entry_code as a
            LEFT JOIN user_bio_entry as b on b.code = a.code AND b.uid = '.$uid.'
            LEFT JOIN bio_entry_value as c on c.value = b.value AND c.bio_entry_code_id = b.code ORDER BY a.code ASC;');

        $response =[
            'personal_information'=> PersonalInformationResource::collection($biodata),
        ];


        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     *   path="/api/get/operator",
     *   summary="get operator from phone number",
     *   tags={"biodata"},
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=true,
     *
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="operator from phone number"
     *   )
     * )
     */
    public function get_operator(Request $request) {
        $validation = Validator::make($request->all(), [
            'phone_number' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),static::CODE_ERROR_VALIDATION);
        }

        $data = [
            'operator' => Utils::get_operator_from_phone($request->phone_number)
        ];

        return $this->successResponse($data);
    }
}