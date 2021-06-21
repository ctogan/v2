<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PulsaGoods extends Model
{
    protected $connection = 'common';
    protected $table = 'pulsa_goods';

    protected $fillable = [
        'id','opcode','goods_id','buy_price','face_price','disabled','live','sell_profit','op_name','op_id','goods_name','server_pulsa','paused','remark_1','order_no','good_code'
    ];
}
