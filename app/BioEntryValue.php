<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BioEntryValue extends Model
{
    protected $connection = 'users';
    protected $table = 'bio_entry_value';

    protected $fillable = [
        'id','bio_entry_code_id','value','value_name'
    ];
}
