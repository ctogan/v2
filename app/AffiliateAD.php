<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateAD extends Model
{
    protected $connection = 'ad';
    protected $table = 'affiliate_ad';

    protected $fillable = [
        'af_id','adid','reward', 'cash_reward_numeric','bonus','img_land','img_cube','status','created_date','modified_date','created_by','share_media','share_web','target_rp',
        'target_count','img_portrait','tm_start','tm_end','merchant','merchant_category', 'title', 'description','icon_type','budget','revenue',
        'pkg_id','browser_type', 'land_param','land_type','land_url','manager','video_url',
        'goal_url','img_ver20','land_config','impression_url','steps','show_on','type','category','img_icon','is_active','show_only_list',
        'webtarget','how_to_description','unique_id'
    ];
}
