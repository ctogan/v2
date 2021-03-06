<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserName extends Model
{
    protected $connection = 'users';
    protected $table = 'user_name';
    public $timestamps = false;
    protected $fillable = [
        'uid',
        'name',
        'tm',
        'bid_change',
        'id_number',
        'password',
        'img',
        'verified',
        'id_card_image',
        'npwp',
        'verified_date',
        'privacy_accepted',
        'email',
        'instagram_account',
        'sex',
        'pob',
        'dob',
        'province_id',
        'city_id',
        'address',
        'weight',
        'height',
        'religion',
        'skills',
        'hobby',
        'last_education',
        'job_part_time_complete',
        'profile',
        'school_name',
        'major',
        'year_of_entry',
        'graduated_year'
    ];
}
