<?php

namespace App\Jobs;

use App\SmsBlast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNumber, $message,$uid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneNumber, $message,$uid)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $endpoint = "https://alpha.zenziva.net/apps/smsapi.php";
        $client = new \GuzzleHttp\Client();
        $userkey = "dwzrko";
        $passkey = "kotakasabla";

        $response = $client->request('POST', $endpoint, [
            'form_params' => [
                'userkey' => $userkey,
                'passkey' => $passkey,
                'nohp' => $this->phoneNumber,
                'pesan' => $this->message
            ]
        ]);

        SmsBlast::where([
            ['uid', $this->uid ],
        ])->update
        ([
            "status" => 'send',
            "updated_at"=>date('Y-m-d H:i:s'),
        ]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

    }
}
