<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LayoutSetting extends Model
{
    protected $connection = 'common';
    protected $table = 'layout_settings';

    protected $fillable = [
        'page_name','sequence','updated_at','updated_by'
    ];
}
