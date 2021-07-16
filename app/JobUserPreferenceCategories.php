<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobUserPreferenceCategories extends Model
{
    protected $connection = 'common';
    protected $table = 'job_user_preference_category';
    protected $fillable = [
        'id','uid','id_education','created_at'
    ];
}
