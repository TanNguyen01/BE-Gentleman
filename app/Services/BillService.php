<?php

namespace App\Services;

use App\Models\Bill;

class BillService extends AbstractServices {
    public function __construct(Bill $bill)
    {
        parent::__construct($bill);
    }

    public function getAllBill(){
        return $this->eloquentGetAll();
    }

    public function storeBill($data){
        return $this->eloquentPostCreate($data);
    }

    public function showBill($id){
        return $this->eloquentFind($id);
    }

   public function eloquentBillWithBillDetail($id){
        $relations = ['billDetails'];
        return $this->eloquentWithRelations($id,$relations);
   }
}
