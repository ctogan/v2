<?php
namespace App\Helpers;

use App\City;
use App\CompanyCategory;
use App\JobCompany;
use App\JobEducation;
use App\Province;

class Cache {
    protected const SES_GET_PROVINCE="__sess__get__province";
    protected const SES_GET_ALL_CITY="__sess__get__all_city";
    protected const SES_GET_CATEGORY="__sess__get__all_category";
    protected const SES_GET_COMPANY_REGISTERED="__sess__get__company_registered";
    protected const SES_GET_EMPLOYEE_REGISTERED="__sess__get__employee_registered";
    protected const SES_EDUCATION="__sess__get__education";

    public static function get_province() {
        $province = \Illuminate\Support\Facades\Cache::get(static::SES_GET_PROVINCE);
        if(!$province){
            $province = Province::where('row_status','=','active')->get();
            \Illuminate\Support\Facades\Cache::put(static::SES_GET_PROVINCE, $province, 36000);
        }

        return $province;
    }

    public static function get_all_city() {
        $city = \Illuminate\Support\Facades\Cache::get(static::SES_GET_ALL_CITY);
        if(!$city){
            $city = City::where('row_status','=','active')->get();
            \Illuminate\Support\Facades\Cache::put(static::SES_GET_ALL_CITY, $city, 36000);
        }

        return $city;
    }

    public static function get_city($province_id) {
        $city = \Illuminate\Support\Facades\Cache::get(static::SES_GET_ALL_CITY.$province_id);
        if(!$city){
            $city = City::where('province_id','=',$province_id)->get();
            \Illuminate\Support\Facades\Cache::put(static::SES_GET_ALL_CITY.$province_id, $city, 36000);
        }

        return $city;
    }

    public static function get_category(){
        $category = \Illuminate\Support\Facades\Cache::get(static::SES_GET_CATEGORY);
        if(!$category){
            $category = CompanyCategory::where('row_status','=','active')->get();
            \Illuminate\Support\Facades\Cache::put(static::SES_GET_CATEGORY, $category, 36000);
        }

        return $category;
    }

    public static function get_company(){
        $company = \Illuminate\Support\Facades\Cache::get(static::SES_GET_COMPANY_REGISTERED);
        if(!$company){
            $company = JobCompany::where('row_status','=','active')->get();
            \Illuminate\Support\Facades\Cache::put(static::SES_GET_COMPANY_REGISTERED, $company, 36000);
        }

        return $company;
    }

    public static function get_company_by_id($id){
        $company = \Illuminate\Support\Facades\Cache::get(static::SES_GET_COMPANY_REGISTERED.$id);
        if(!$company){
            $company = JobCompany::where('id','=',$id)->first();
            \Illuminate\Support\Facades\Cache::put(static::SES_GET_COMPANY_REGISTERED.$id, $company, 36000);
        }

        return $company;
    }

    public static function get_education(){
        $education = \Illuminate\Support\Facades\Cache::get(static::SES_EDUCATION);
        if(!$education){
            $education =JobEducation::get();
            \Illuminate\Support\Facades\Cache::put(static::SES_EDUCATION, $education, 36000);
        }

        return $education;
    }

    public static function is_regis_part_time($uid){
        return true;
    }

}
