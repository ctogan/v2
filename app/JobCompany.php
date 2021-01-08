<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCompany extends Model
{
    protected $connection = 'common';
    protected $table = 'job_company';

    protected $fillable = [
        'row_status','uid','company_name','company_logo','category', 'address','province_id','city_id','description','email','phone_number','website','created_at','created_by','updated_at','updated_by'
    ];
}