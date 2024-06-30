<?php

namespace App\Services;

use App\Models\BillDetail;
use Illuminate\Support\Facades\DB;
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
        foreach ($data['data'] as $key => $variantData) {
            $result = $this->variantService->updateQuantityWithBill($variantData['variant_id'], $variantData['quantity']);
            unset($data['data'][$key]['variant_id']);
            if ($result['code'] == 201) {
                return $result;
            }
        }



        return [
            'status' => $this->eloquentMutiInsert($data['data'])
        ];
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
