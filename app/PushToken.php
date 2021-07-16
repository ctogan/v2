<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushToken extends Model
{
    protected $connection = 'users';
    protected $table = 'push_tokens';

    protected $primaryKey = 'uid';
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'uid','failed','token'
    ];
}
