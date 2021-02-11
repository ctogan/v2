<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobMainCategory extends Model
{
    protected $connection = 'common';
    protected $table = 'job_company_main_category';
    protected $fillable = [
        'name','name_main_category','created_at','row_status'
    ];

    public function get_category_child(){
        return $this->hasMany('App\CompanyCategory','id_main_category');
    }
}
