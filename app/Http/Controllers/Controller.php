<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const ERROR_NOT_FOUND = "Not Found";
    protected const TRANSACTION_SUCCESS = "Transaction Successfully";
    protected const TRANSACTION_ERROR = "Transaction Error";
    protected const TRANSACTION_ERROR_NOT_FOUND = "Data Not Found";
    protected const TRANSACTION_ERROR_VALIDATION = "Silahkan isi semua kolom.";
    protected const WAPI_ERROR_DESC = "Erorr When try to process";
    protected const TRANSACTION_ERROR_USER_BLOCK = " User Block Login";
    protected const TRANSACTION_ERROR_USER_NEED_LOGIN = "Need User Login";
    protected const TRANSACTION_ERROR_GENERAL = "Terjadi kesalahan saat proses";
    protected const OUT_OF_STOCK = "Voucher out of stock";
    protected const USER_EMAIL_EXIST = "Email sudah pernah di daftar";
    protected const ERROR_PIN = 'Pin Salah';
    protected const SUCCESS_PIN = 'Pin Sukses';
    protected const ERROR_PIN_DESC = 'Pin Salah';
    protected const SUCCESSPIN_DESC = 'Pin Salah';
    protected const REFUND_ERROR_FAILED = 'Anda hanya memiliki kesempatan 5 kali untuk Refund';


    // TRANSACTION
    protected const CODE_ERROR_ORDER_NUMBER_NOT_FOUND = '401';
    protected const CODE_SUCCESS = '200';
    protected const CODE_ERROR_VALIDATION = 'E201';
    protected const ERROR_USER_AUTH = '202';
    protected const ERROR_USER_BLOCK = '203';
    protected const ERROR_USER_NEED_LOGIN = '204';
    protected const ERROR_USER_POINT = '205';
    protected const ERROR_OUT_OF_STOCK = '207';
    protected const ERROR_GIFTN_WAPI = '208';
    protected const ERROR_TRANSACTION_CONFIRM_VALIDATION = '209';
    protected const ERROR_TRANSACTION_ERROR_GENERAL_CODE = '210';
    protected const ERROR_USER_EMAIL_EXIST = '211';
    protected const ERROR_PIN_CODE = '212';
    protected const ERROR_MAINTENANCE = '213';
    protected const CODE_REFUND_ERROR_FAILED = '214';


    // MESAAGE
    protected  const MSG_ORDER_SUCCESS_PENDING_TITLE = 'Pemesanan';
    protected  const MSG_ORDER_SUCCESS_PENDING_BODY = 'Invoice Transaksi %s, Silahkan lakukan pembayaran paling lambat 1X24 jam';

    protected const CODE_POINT_EARN_CASHBACK = 'EARN001';
    protected const CODE_POINT_EARN_REFUND = 'EARN002';
    protected const CODE_POINT_EARN_REJECT_ORDER = 'EARN003';
    protected const CODE_POINT_USE = 'USE001';

    public function merge_response($data, $config = null){
        if($config == null){
            return $data;
        }

        return array_merge($data,$config);
    }

    public function single_message($message){
        return [array($message)];
    }

    public function forget_cache($key){
        Cache::forget($key);
    }
}
