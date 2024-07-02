<?php

namespace App\Services;

use App\Http\Requests\BillDetailRequest;
use App\Jobs\SendMail;
use App\Mail\BillConfirmationMail;
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
            // Lấy thông tin người dùng hiện tại
            $user = Auth::user();// hoặc Auth::guard('user')->user(); nếu bạn sử dụng guard 'user'

            foreach ($data['data'] as $key => $variantData) {
                $result = $this->variantService->updateQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
                unset($data['data'][$key]['variant_id']);
                if ($result['code'] == 201) {
                    return $result;
                }
            }

            // Lưu chi tiết hóa đơn
            $status = $this->eloquentMutiInsert($data['data']);

            // Chuẩn bị dữ liệu cho email
            $billDetails = $data['data'];

            // Gửi email xác nhận hóa đơn
           // Mail::to($data['email'])->send(new BillConfirmationMail($billDetails, $user));

            SendMail::dispatch( $billDetails, $user)->delay(now()->addSeconds(2));

            return [
                'status' => $status
            ];

        } catch (\Exception $e) {
            // Ghi nhật ký lỗi và trả về phản hồi lỗi
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
