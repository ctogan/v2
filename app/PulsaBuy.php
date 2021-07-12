<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PulsaBuy extends Model
{
    protected $connection = 'common';
    protected $table = 'pulsa_buy';

    public $timestamps = false;

    protected $fillable = [
        'uid','dt','pulsa_goods_id','is_auto','tm','run_at','complete','changed','cash','status','req_count','phone','res_code','tr_id','additional_1','flash_detail_code'
    ];

}
