<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\VnpayService;
use Illuminate\Http\Request;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

class VnpayController extends Controller
{
    use APIResponse;
    protected $vnpay;
    function __construct()
    {
        $this->vnpay = new VnpayService;
    }
    public function checkout(Request $request)
    {
        $response = $this->vnpay->pay($request);
        return $this->responseSuccess(
            __('thanh toan thanh cong'),
            [
                'data' => $response,
            ]
        );;
    }
}
