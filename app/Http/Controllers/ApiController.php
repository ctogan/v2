<?php

namespace App\Http\Controllers;

use App\Helpers\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponser;

    protected $user;

    protected const ERROR_NOT_FOUND = "Not Found";
    protected const TRANSACTION_SUCCESS = "Transaction Successfully";
    protected const TRANSACTION_ERROR = "Transaction Error";
    protected const TRANSACTION_ERROR_NOT_FOUND = "Data Not Found";
    protected const PROFILE_UNCOMPLETE = "YOUR DATA UNCOMPLETE";
    protected const ERROR_DATA_SAVE = "Error when saving data";

    protected const CODE_SUCCESS = 200;
    protected const CODE_ERROR_VALIDATION = 201;
    protected const PROFILE_UNCOMPLETE_CODE = 202;
    protected const ERROR_DATA_SAVE_CODE = "203";

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = User::session($request);

            if(!$user['status']){
                return $this->errorResponse($user['message'],200);
            }

            $this->user = $user['data'];

            return $next($request);
        });
    }
}
