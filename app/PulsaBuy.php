<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PulsaBuy extends Model
{
    protected $connection = 'common';
    protected $table = 'pulsa_buy';

    protected $fillable = [
        'seq','uid','dt','pulsa_good_id','is_auto','tm','run_at','complete','changed','cash','status','req_count','phone','res_code','tr_id','additional_1'
    ];

}
