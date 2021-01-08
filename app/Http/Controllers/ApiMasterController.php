<?php

namespace App\Http\Controllers;

use App\Helpers\CtreeCache;
use App\Province;
use Illuminate\Http\Request;

class ApiMasterController extends ApiController
{
    public function get_province(Request $request){
//        $province = CtreeCache::get_province(false);
        $province = Province::where('row_status','=','active')->select('id','province_name')->get();
        $reponse =[
            'province'=>$province
        ];

        return $this->successResponse($reponse, static::TRANSACTION_SUCCESS);
    }

    public function get_city(Request $request){
        $city = CtreeCache::get_city($request->province_id , false);
        $reponse =[
            'city'=>$city
        ];

        return $this->successResponse($reponse, static::TRANSACTION_SUCCESS);
    }

    public function get_company_category(Request $request){
        $category = CtreeCache::get_category(false);
        $reponse =[
            'category'=>$category
        ];

        return $this->successResponse($reponse, static::TRANSACTION_SUCCESS);
    }

}
