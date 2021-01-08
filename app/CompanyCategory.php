<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyCategory extends Model
{
    protected $connection = 'common';
    protected $table = 'job_company_category';

    protected $fillable = [
        'row_status','category_name'
    ];
}
