<?php

namespace App\Http\Controllers\Api;

use App\BioEntryCode;
use App\BioEntryValue;
use App\CCSession;
use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalInformationResource;
use App\PersonalInformation;
use Illuminate\Http\Request;
use DB;

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

        $biodata = DB::connection('users')->select('select user_bio_entry.uid,user_bio_entry.code,bio_entry_code.code_name,user_bio_entry.value,bio_entry_value.value_name from user_bio_entry
            left join bio_entry_code ON bio_entry_code.code = user_bio_entry.code
            left join bio_entry_value ON bio_entry_value.value = user_bio_entry.value
            where user_bio_entry.uid = '.$uid.' and bio_entry_value.bio_entry_code_id = user_bio_entry.code
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

        $uid = $this->user->uid;
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

        $biodata = DB::connection('users')->select('select user_bio_entry.uid,user_bio_entry.code,bio_entry_code.code_name,user_bio_entry.value,bio_entry_value.value_name from user_bio_entry
            left join bio_entry_code ON bio_entry_code.code = user_bio_entry.code
            left join bio_entry_value ON bio_entry_value.value = user_bio_entry.value
            where user_bio_entry.uid = '.$uid.' and bio_entry_value.bio_entry_code_id = user_bio_entry.code
        ');

        $response =[
            'personal_information'=> PersonalInformationResource::collection($biodata),
        ];


        return $this->successResponse($response);
    }



}
