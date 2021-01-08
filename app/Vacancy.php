<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $connection = 'common';
    protected $table = 'job_vacancy';

    protected $fillable = [
        'row_status','vacancy_status','company_id','province_id','city_id', 'position_name','description','qualifications','education','experienced','salary','salary_type','allowance','send_to_email','send_to_wa','active_until','rejection_reason','approved_by','approved_at','rejected_by','rejected_at','created_by','created_at','updated_at','updated_by'
    ];
}
