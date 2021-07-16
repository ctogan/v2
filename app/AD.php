<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AD extends Model
{
    protected $connection = 'ad';
    protected $table = 'ad';

    protected $fillable = [
        'adid','legacy_id','enabled', 'status','priority','tm_start','tm_end', 'tm_modified', 'tm_visible_start','tm_visible_end',
        'type','category','merchant','merchant_category', 'title', 'description','icon_type','budget','revenue','left_reward',
        'alert_enable','alert_title','alert_description', 'bgcolor','img_banner','img_icon','img_large','img_small','pkg_id','browser_type',
        'land_param','land_type','land_url','show_after_ad','memo_text','show_only_list','manager','daily_cap','video_url',
        'goal_url','img_ver20','show_on','step','display_reward','land_config','bgccolor2','impression_url'
    ];
}
