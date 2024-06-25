<?php

namespace App\Services;

use App\Models\BillDetail;

class BillDetailService extends AbstractServices {
    private $variantService;
    public function __construct(BillDetail $billDetail, VariantService $variantService)
    {
        parent::__construct($billDetail);
        $this->variantService = $variantService;
    }

    public function getAllBillDetail(){
        return $this->eloquentGetAll();
    }

    public function storeBillDetail($data){
        foreach ($data['variants'] as $variantData) {
            $result = $this->variantService->updateQuantityWithBill($variantData['id'], $variantData['quantity']);
            if (is_array($result) && $result['status'] === 'khong du so luong') {
                return $result;
            }
        }
        return $this->eloquentMutiInsert($data);
    }

    public function showBillDetail($id){
        return $this->eloquentFind($id);
    }

    public function updateBillDetail($id, $data){
        return $this->eloquentUpdate($id,$data);
    }

    public function destroyBillDetail($id){
        return $this->eloquentDelete($id);
    }
}
