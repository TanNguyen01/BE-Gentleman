<?php

namespace App\Services;

use App\Models\BillDetail;

class BillDetailService extends AbstractServices {
    public function __construct(BillDetail $billDetail)
    {
        parent::__construct($billDetail);
    }

    public function getAllBillDetail(){
        return $this->eloquentGetAll();
    }

    public function storeBillDetail($data){
        return $this->eloquentPostCreate($data);
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
