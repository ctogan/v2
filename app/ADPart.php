<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ADPart extends Model
{
    protected $table = 'user_part';

    protected $fillable = [
        'seq','uid','adid','status','tm','install','needreward','complete','sub1','sub2','sub3','sub4','sub5','sub6','sub7','sub8'
    ];
}