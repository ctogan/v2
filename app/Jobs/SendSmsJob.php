<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Nasution\Zenziva\Zenziva;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    const ZENZIVA_USER_KEY = 'dwzrko';
    const ZENZIVA_PASS_KEY = 'kotakasabla';
    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $msg = $this->details['message'];
//        $num = $this->details['phone_number'];
//
//
//        $url = 'https://alpha.zenziva.net/apps/smsapi.php?'.http_build_query([
//                'userkey' => 'dwzrko',
//                'passkey' => 'kotakasabla',
//                'nohp' => "0".$num,
//                'pesan' => $msg,
//                'type' => 'Notification'
//            ]);
//
//        $res = HTTPFactory::get($url);
//        return !!strpos($res[0], 'status');
        require 'vendor/autoload.php';
        $zenziva = new Zenziva('dwzrko', 'kotakasabla');
        $zenziva->sms('085275608369', 'Halo');

    }
}
