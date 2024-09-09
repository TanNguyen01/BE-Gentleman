<?php

namespace App\Services;

use App\Events\BillDetailsSaved;
//use App\Events\DashboardEvent;
use App\Jobs\SendMail;
use App\Mail\BillConfirmationMail;
use App\Models\Bill;
use App\Models\BillDetail;
use Illuminate\Support\Facades\Auth;
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
            if (!Auth::check()) {
                return [
                    'status' => 'error',
                    'message' => 'Người dùng chưa đăng nhập!'
                ];
            }
            if (!isset($data['data']) || empty($data['data'])) {
                return [
                    'status' => 'error',
                    'message' => 'Dữ liệu chi tiết hóa đơn không hợp lệ'
                ];
            }

            if (!isset($data['bill_id'])) {
                return [
                    'status' => 'error',
                    'message' => 'bill_id không tồn tại trong dữ liệu đầu vào'
                ];
            }

            $bill = Bill::with('billDetails')->findOrFail($data['bill_id']);
            $user = Auth::user();

            $billDetails = [];
            foreach ($data['data'] as $variantData) {
                $result = $this->variantService->updateQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
                if (!isset($result['code']) || $result['code'] != 200) {
                    return [
                        'status' => 'error',
                        'message' => $result['message'] ?? 'Lỗi cập nhật số lượng'
                    ];
                }

                $billDetail = new BillDetail();
                $billDetail->product_name = $variantData['product_name'];
                $billDetail->attribute = $variantData['attribute'];
                $billDetail->price = $variantData['price'];
                $billDetail->quantity = $variantData['quantity'];
                $billDetail->bill_id = $bill->id;
                $billDetail->sale = $variantData['sale'];
                $billDetail->image = $variantData['image'];
                $billDetail->price_origin = $variantData['price_origin'];
                $billDetails[] = $billDetail;
            }

            // Sử dụng saveMany để lưu nhiều bản ghi cùng lúc
            $status = $bill->billDetails()->saveMany($billDetails);
            if (!$status) {
                foreach ($data['data'] as $variantData) {
                    try {
                        $this->variantService->rollbackQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
                    } catch (\Exception $rollbackException) {
                        Log::error('Lỗi rollback số lượng: ' . $rollbackException->getMessage());
                    }
                }
                return [
                    'status' => 'error',
                    'message' => 'Lỗi khi lưu chi tiết hóa đơn. Số lượng đã được hoàn lại.'
                ];
            } else {
                // Phát ra sự kiện để gửi email
                event(new BillDetailsSaved($bill, $user, $billDetails));

                return [
                    'status' => true,
                    'message' => 'Chi tiết hóa đơn đã được lưu thành công và email đã được gửi.'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Lỗi lưu chi tiết hóa đơn: ' . $e->getMessage(), [
                'bill_id' => $data['bill_id'] ?? 'N/A',
                'user_id' => Auth::id() ?? 'N/A',
            ]);

            return [
                'status' => 'error',
                'message' => 'Lỗi khi lưu chi tiết hóa đơn. Vui lòng thử lại.'
            ];
        }
    }











    public function showBillDetail1($bill_id)
    {
        // Assuming 'bill_id' is a column in the 'bill_details' table
        $billDetails = BillDetail::where('bill_id', $bill_id)->get();

        if ($billDetails->isEmpty()) {
            return response()->json(['message' => 'Bill details not found'], 404);
        }

        return response()->json($billDetails);
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
