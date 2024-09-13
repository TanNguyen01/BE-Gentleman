<?php

namespace App\Services;

use App\Events\DashboardEvent;
use App\Jobs\SendMail;
use App\Models\Bill;
use App\Models\BillDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                    'message' => 'NgưỞi dùng chưa đăng nhập!'
                ];
            }
            if (!isset($data['bill_id'])) {
                throw new \Exception('bill_id không tồn tại trong dữ liệu đầu vào');
            }
            $bill = Bill::with('billDetails')->findOrFail($data['bill_id']);
            $user = Auth::user();

            $billDetailsData = [];
            foreach ($data['data'] as  $variantData) {
                $result = $this->variantService->updateQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
                if ($result['code'] != 200) {
                    $bill->delete();
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
                ];
            }
            $status = $this->eloquentMutiInsert($billDetailsData);
            if ($status == false || !$status){
                foreach ($data['data'] as  $variantData) {
                    $this->variantService->rollbackQuantityWithBill($variantData['variant_id'],$variantData['quantity']);
                }
                $bill->delete();
            }
            else{
                event(new DashboardEvent());
            //   gui mail
                SendMail::dispatch($billDetailsData, $user, $bill);
            }
            return [
                'status' => $status,
                'message' => 'Chi tiết hóa đơn đã được lưu thành công và email đã được gửi.'
            ];

        } catch (\Exception $e) {
            Log::error('Lỗi lưu chi tiết hóa đơn: ' . $e->getMessage());
            return [
                'status' => 'error',

                'message' => 'Lỗi trong quá trình lưu chi tiết hóa đơn!'
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
