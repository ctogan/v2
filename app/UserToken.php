<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $connection = 'users';
    protected $table = 'push_tokens';
    public $timestamps = false;
    protected $fillable = [
        'uid','failed','token'
    ];
}
