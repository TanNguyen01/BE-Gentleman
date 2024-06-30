<?php
namespace App\Services;

class VnpayService
{
    public function pay($request){
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = $request['data'][0]['returnurl'];
        $vnp_TmnCode = "2OQ5EPNA";//M? website t?i VNPAY
        $vnp_HashSecret = "FSV3D439FAU1EBZ4Y89ZDN9241AMCJL0"; //Chu?i bí m?t

        $vnp_TxnRef =  $request['data'][0]['bill_id'];
        $vnp_OrderInfo = 'Thanh toan online';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (float)$request['data'][0]['amount'] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = $request['data'][0]['bank_code'];
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params
        $inputData = array(
            "vnp_Version" => "2.1.0", //Phiên b?n c? là 2.0.0, 2.0.1 thay ð?i sang 2.1.0
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

    //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";

    //Build querystring phiên b?n m?i 2.1.0

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        header('Location: ' . $vnp_Url);
        die();

    }
}
