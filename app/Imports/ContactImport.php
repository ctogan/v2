<?php

namespace App\Imports;

use App\SmsBlast;
use Maatwebsite\Excel\Concerns\ToModel;

class ContactImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new SmsBlast([

            'uid'               => $row['0'],
            'phone_number'      => $row['1'],
            'point_balance'     => $row['2'],
            'message'           => $row['3'],
            'status'            => $row['4'],
        ]);


    }
}
