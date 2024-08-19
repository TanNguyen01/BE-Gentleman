<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\VnpayServiceNow;
use Illuminate\Http\Request;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

class VnpayNowController extends Controller
{
    use APIResponse;
    protected $vnpay;
    public function __construct()
    {
        $this->vnpay = new VnpayServiceNow();
    }
    public function checkout($bill_id, $amount, $bank_code)
    {
        $data = [];
        $data['bill_id'] = $bill_id;
        $data['amount'] = $amount;
        $data['bank_code'] = $bank_code;
        $response = $this->vnpay->pay($data);
        return $this->responseSuccess(
            __('thanh toan thanh cong'),
            [
                'data' => $response,
            ]
        );
        ;
    }
}