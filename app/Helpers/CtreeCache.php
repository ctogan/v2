<?php
namespace App\Helpers;

use App\City;
use App\CompanyCategory;
use App\JobApplicant;
use App\JobBookmark;
use App\JobCompany;
use App\Province;
use App\Vacancy;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CtreeCache {

    protected const SES_GET_PROVINCE="__sess__get__province";
    protected const SES_GET_ALL_CITY="__sess__get__all_city";
    protected const SES_GET_CITY_BY_ID="__sess__get__city__by_id";
    protected const SES_GET_ALL_LOCATE="__sess__get__all__locate";
    protected const SES_GET_CATEGORY="__sess__get__all_category";
    protected const SES_GET_VACANCY_BY_ID="__sess__get__vacancy__by_id";
    protected const SES_GET_CANDIDATE_BY_VANCANCY_ID="__sess__get__vacancy__by_id";
    protected const SES_GET_COMPANY_REGISTERED="__sess__get__company_registered";
    protected const SES_GET_EMPLOYEE_REGISTERED="__sess__get__employee_registered";
    protected const SES_GET_ALL_PROVINCE_AND_CITY = "__sess__get_province__and_city";
    protected const SES_GET_USER_BY_ID="__sess__get__user__by_id";
    protected const CACHE_PER_MINUTE = 60;
    protected const CACHE_PER_HOURS = 60 * 60;
    protected const CACHE_PER_DAY = 60 * 60 * 24;
    protected const CACHE_PER_WEEK = 60 * 60 * 24 * 7;
    protected const CACHE_PER_MONTH = 60 * 60 * 24 * 7 * 30;
    protected const CACHE_PER_YEAR = 60 * 60 * 24 * 7 * 30 *12;

    public static function get_province($forget = false) {
        if($forget) static::forget_cache(static::SES_GET_PROVINCE);
        $province = Cache::get(static::SES_GET_PROVINCE);
        if(!$province){
           $province = Province::where('row_status','=','active')->select('id','province_name')->get();
           Cache::put(static::SES_GET_PROVINCE , $province , static::CACHE_PER_MONTH);
        }
        return $province;
    }

    public static function get_all_city($forget=false) {
        if($forget) static::forget_cache(static::SES_GET_ALL_CITY);
        $result = Cache::get(static::SES_GET_ALL_CITY);
        if(!$result){
            $result = City::where('row_status','=','active')->get();
            Cache::put(static::SES_GET_ALL_CITY , $result , static::CACHE_PER_MONTH);
        }
        return $result;
    }

    public static function get_all_province_and_city($forget=false) {
        if($forget) static::forget_cache(static::SES_GET_ALL_PROVINCE_AND_CITY);
        $result = Cache::get(static::SES_GET_ALL_PROVINCE_AND_CITY);
        if(!$result){
            $result = Province::where('province.row_status','=','active')
                ->leftjoin('city' , 'province.id' , '=' , 'city.province_id')
                ->select('province.id' , 'province.province_name' ,'city.id as city_id','city.city_name')
                ->get()->toArray();
            Cache::put(static::SES_GET_ALL_PROVINCE_AND_CITY , $result , static::CACHE_PER_MONTH);
        }
        return $result;
    }

    public static function get_city($province_id, $forget=false) {
        if($forget) static::forget_cache(static::SES_GET_CITY_BY_ID);
        $result = Cache::get(static::SES_GET_CITY_BY_ID);
        if(!$result){
            $result = City::where('province_id','=',$province_id)->select('province_id','id','city_name')->get();
            Cache::put(static::SES_GET_CITY_BY_ID , $result , static::CACHE_PER_MONTH);
        }
        return $result;
    }

    public static function get_all_location( $forget=false)
    {
        if($forget) static::forget_cache(static::SES_GET_ALL_LOCATE);
        $result = Cache::get(static::SES_GET_ALL_LOCATE);
        if(!$result){

            $provincies = Province::where('row_status','=','active')->select('id','province_name')->get();
            $result = [];
            $city_name =[];
            foreach ($provincies as $province)
            {
                $cities = City::where('province_id','=',$province->id)->where('row_status','=','active')->get();
                $fc = City::where('province_id',$province->id)->first();


                foreach ($cities as $city){
                    if($fc){
                        $city_name[] = ['id' => $city->id , 'city_name'=> $city->city_name];
                    }

                }
                $result[] = [
                    'province'      => ['id' => $province->id , 'province_name'=> $province->province_name , 'city'=> $city_name ],
                ];

                $city_name = null;

            }
            Cache::put(static::SES_GET_CITY_BY_ID , $result , static::CACHE_PER_MONTH);
        }

        return $result;

    }

    public static function get_category($forget=false){
        if($forget) static::forget_cache(static::SES_GET_CATEGORY);
        $result = Cache::get(static::SES_GET_CATEGORY);
        if(!$result){
            $result = CompanyCategory::where('row_status','=','active')->select('id','category_name')->get();
            Cache::put(static::SES_GET_CATEGORY , $result , static::CACHE_PER_MONTH);
        }
        return $result;
    }


    public static function get_company(){
        return JobCompany::where('row_status','=','active')->get();
    }

    public static function get_company_by_id($id){
        return JobCompany::where('id','=',$id)->first();
    }

    public static function is_regis_part_time($uid){
        return true;
    }

    public static function get_job_vacancy_by_id($vacancy_id, $is_submitted, $forget=false){
     /* if($forget) static::forget_cache(static::SES_GET_VACANCY_BY_ID.'_'.$vacancy_id);
        $result = Cache::get(static::SES_GET_VACANCY_BY_ID.'_'.$vacancy_id);
        if(!$result){
            $result = Vacancy::where('job_vacancy.id','=',$vacancy_id)
            ->join('city','job_vacancy.city_id','city.id')
            ->join('job_company','job_company.id','job_vacancy.company_id')
            ->select('job_vacancy.*','job_company.company_name','city.city_name')
            ->first();
            if($result){
                $result->toArray();
            }
            Cache::put(static::SES_GET_VACANCY_BY_ID.'_'.$vacancy_id , $result , static::CACHE_PER_MONTH);
        }*/
        $result = Vacancy::where('job_vacancy.id','=',$vacancy_id)
            ->join('city','job_vacancy.city_id','city.id')
            ->join('province', 'job_vacancy.province_id', 'province.id')
            ->join('job_company','job_company.id','job_vacancy.company_id')
            ->select('job_vacancy.*','job_company.company_name','city.city_name', 'province.province_name')
            ->first();
        if($result){
            $result->is_submitted = $is_submitted;
            $result->toArray();
        }
        return $result;
    }

    public static function get_candidate_vacancy($vacancy_id , $forget = false){
        if($forget){
            static::forget_cache(static::SES_GET_CANDIDATE_BY_VANCANCY_ID.'_'.$vacancy_id);
        }

        $result = Cache::get(static::SES_GET_CANDIDATE_BY_VANCANCY_ID.'_'.$vacancy_id);

        if(!$result){
            $result = JobApplicant::where('job_applicant.vacancy_id','=',$vacancy_id)
                ->join('job_education','job_education.id','job_applicant.last_education')
                ->select('job_applicant.*','job_education.education')
                ->get();
            Cache::put(static::SES_GET_CANDIDATE_BY_VANCANCY_ID.'_'.$vacancy_id , $result , static::CACHE_PER_MONTH);
        }

        $user = [];
        if($result){
            foreach($result as $applicant_detail){
                $obj_user = static::user_cache($applicant_detail->uid, $forget);
                $obj_user->religion_text = $applicant_detail->religion;
                $obj_user->last_education_text = $applicant_detail->education;
                $user[] = $obj_user;
            }
        }
        return $user;
    }

    public static function clear_candidate_vacancy($vacancy_id){
        static::forget_cache(static::SES_GET_CANDIDATE_BY_VANCANCY_ID.'_'.$vacancy_id);
    }

    public static function user_cache($uid, $forget = false){
        if($forget){
            static::forget_cache(static::SES_GET_USER_BY_ID.'_'.$uid);
        }
        $result = Cache::get(static::SES_GET_USER_BY_ID.'_'.$uid);
        if(!$result){
            $result = DB::connection("users")->table("view_user_2")->where("uid",'=',$uid)->first();
            Cache::put(static::SES_GET_USER_BY_ID.'_'.$uid , $result , static::CACHE_PER_HOURS);
        }
        return $result;
    }

    static function forget_cache($code){
        Cache::forget($code);
    }

}
