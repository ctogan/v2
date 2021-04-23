<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicSection extends Model
{
    protected $connection = 'common';
    protected $table = 'dynamic_sections';

    protected $fillable = [
        'row_status','title','sub_title', 'target','url','deeplink','snapcash_id','adid','dynamic_section_img','created_by','created_at', 'updated_by', 'updated_at'
    ];
}