<?php

namespace App\Services;

use App\Http\Requests\BillDetailRequest;
use App\Jobs\SendMail;
use App\Mail\BillConfirmationMail;
use App\Models\Bill;
use App\Models\BillDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BillDetailService extends AbstractServices
{
    private $variantService;
    public function __construct(BillDetail $billDetail, VariantService $variantService)
    {
        parent::__construct($billDetail);
        $this->variantService = $variantService;
    }

    public function getAllBillDetail()
    {
        return $this->eloquentGetAll();
    }


    public function storeBillDetail($data)
    {

        try {
            // Kiểm tra ngưỞi dùng có đăng nhập hay không
            if (!Auth::check()) {
                return [
                    'status' => 'error',
                    'message' => 'NgưỞi dùng chưa đăng nhập!'
                ];
            }

            // Ghi log dữ liệu đầu vào để kiểm tra
            Log::info('Dữ liệu đầu vào: ' . json_encode($data));

            // Kiểm tra và ghi log bill_id
            if (!isset($data['bill_id'])) {
                throw new \Exception('bill_id không tồn tại trong dữ liệu đầu vào');
            }

            Log::info('bill_id: ' . $data['bill_id']);

            // Lấy hóa đơn
            $bill = Bill::with('billDetails')->findOrFail($data['bill_id']);
            Log::info('Hóa đơn tìm thấy: ' . json_encode($bill));

            // Lấy thông tin ngưỞi dùng hiện tại
            $user = Auth::user();

            $billDetailsData = [];

            // Cập nhật số lượng và chuẩn bị dữ liệu chi tiết hóa đơn
            foreach ($data['data'] as  $variantData) {
                $result = $this->variantService->updateQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
                if ($result['code'] == 201) {
                    return $result;
                }

                $billDetailsData[] = [
                    'product_name' => $variantData['product_name'],
                    'attribute' => $variantData['attribute'],
                    'price' => $variantData['price'],
                    'quantity' => $variantData['quantity'],
                    'bill_id' => $bill->id,
                    'sale' => $variantData['sale'],
                    'image' => $variantData['image'],
                    'price_origin'=> $variantData['price_origin'],
                    // Các trưỞng khác nếu có
                ];
            }

            // Lưu chi tiết hóa đơn
            $status = $this->eloquentMutiInsert($billDetailsData);
            if ($status == false){
                Log::info("add-billDeatil-done");
                foreach ($data['data'] as  $variantData) {
                    $this->variantService->rollbackQuantityWithBill($variantData['variant_id'],$variantData['quantity']);
                }
            }
            else{
            //   gui mail
                Log::info("begin-sent-email");
                SendMail::dispatch($billDetailsData, $user, $bill);
            }

            Log::info("end-sent-email");
            return [
                'status' => $status,
                'message' => 'Chi tiết hóa đơn đã được lưu thành công và email đã được gửi.'
            ];

        } catch (\Exception $e) {
            // Ghi nhật ký lỗi và trả vỞ phản hồi lỗi
            Log::error('Lỗi lưu chi tiết hóa đơn: ' . $e->getMessage());
            return [
                'status' => 'error',

                'message' => 'Lỗi trong quá trình lưu chi tiết hóa đơn!'
            ];
        }
    }






    public function showBillDetail($id)
    {
        return $this->eloquentFind($id);
    }

    public function updateBillDetail($id, $data)
    {
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyBillDetail($id)
    {
        return $this->eloquentDelete($id);
    }
}
