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
                    'message' => 'NgÆ°á»�i dĂ¹ng chÆ°a Ä‘Äƒng nháº­p!'
                ];
            }
            if (!isset($data['data']) || empty($data['data'])) {
                return [
                    'status' => 'error',
                    'message' => 'Dá»¯ liá»‡u chi tiáº¿t hĂ³a Ä‘Æ¡n khĂ´ng há»£p lá»‡'
                ];
            }

            if (!isset($data['bill_id'])) {
                return [
                    'status' => 'error',
                    'message' => 'bill_id khĂ´ng tá»“n táº¡i trong dá»¯ liá»‡u Ä‘áº§u vĂ o'
                ];
            }

            $bill = Bill::with('billDetails')->findOrFail($data['bill_id']);
            $user = Auth::user();

            $billDetails = [];
            foreach ($data['data'] as $variantData) {
                $result = $this->variantService->updateQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
                if (!isset($result['code']) || $result['code'] != 200) {
                    $bill->delete();
                    return [
                        'status' => 'error',
                        'message' => $result['message'] ?? 'Lá»—i cáº­p nháº­t sá»‘ lÆ°á»£ng'
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

            // Sá»­ dá»¥ng saveMany Ä‘á»ƒ lÆ°u nhiá»�u báº£n ghi cĂ¹ng lĂºc
            $status = $bill->billDetails()->saveMany($billDetails);
            if (!$status) {
                $bill->delete();
                foreach ($data['data'] as $variantData) {
                    try {
                        $this->variantService->rollbackQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
                    } catch (\Exception $rollbackException) {
                        Log::error('Lá»—i rollback sá»‘ lÆ°á»£ng: ' . $rollbackException->getMessage());
                    }
                }
                return [
                    'status' => 'error',
                    'message' => 'Lá»—i khi lÆ°u chi tiáº¿t hĂ³a Ä‘Æ¡n. Sá»‘ lÆ°á»£ng Ä‘Ă£ Ä‘Æ°á»£c hoĂ n láº¡i.'
                ];
            } else {
                // PhĂ¡t ra sá»± kiá»‡n Ä‘á»ƒ gá»­i email
                event(new BillDetailsSaved($bill, $user, $billDetails));

                return [
                    'status' => true,
                    'message' => 'Chi tiáº¿t hĂ³a Ä‘Æ¡n Ä‘Ă£ Ä‘Æ°á»£c lÆ°u thĂ nh cĂ´ng vĂ  email Ä‘Ă£ Ä‘Æ°á»£c gá»­i.'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Lá»—i lÆ°u chi tiáº¿t hĂ³a Ä‘Æ¡n: ' . $e->getMessage(), [
                'bill_id' => $data['bill_id'] ?? 'N/A',
                'user_id' => Auth::id() ?? 'N/A',
            ]);

            return [
                'status' => 'error',
                'message' => 'Lá»—i khi lÆ°u chi tiáº¿t hĂ³a Ä‘Æ¡n. Vui lĂ²ng thá»­ láº¡i:'. $e->getMessage()
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
