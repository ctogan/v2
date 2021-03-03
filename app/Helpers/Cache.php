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

    public static function get_province() {
        return Province::where('row_status','=','active')->get();
    }

    public static function get_all_city() {
        return City::where('row_status','=','active')->get();
    }

    public static function get_city($province_id) {
        return City::where('province_id','=',$province_id)->get();
    }

    public static function get_category(){
        return CompanyCategory::where('row_status','=','active')->get();
    }

    public static function get_company(){
        return JobCompany::where('row_status','=','active')->get();
    }

    public static function get_company_by_id($id){
        return JobCompany::where('id','=',$id)->first();
    }

    public static function get_education(){
        return JobEducation::get();
    }

    public static function is_regis_part_time($uid){
        return true;
    }

}
