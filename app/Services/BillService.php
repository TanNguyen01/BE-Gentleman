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

   public function chanceStatusConfirm($id){
    $res = $this->eloquentFind($id);
    $res->status = 'confirm';
    $res->save();
    return 'confirm';
}

public function chanceStatusShiping($id){
    $res = $this->eloquentFind($id);
    $res->status = 'Shiping';
    $res->save();
    return 'confirm';
}

public function chanceStatusPaid($id){
    $res = $this->eloquentFind($id);
    $res->status = 'Paid';
    $res->save();
    return 'confirm';
}

public function chanceStatusPaidShiping($id){
    $res = $this->eloquentFind($id);
    $res->status = 'PaidShiping';
    $res->save();
    return 'confirm';
}

public function chanceStatusCancel($id){
    $res = $this->eloquentFind($id);
    $res->status = 'Cancel';
    $res->save();
    return 'confirm';
}

public function getStatusPaid(){
    $res = $this->eloquentWhere('status','Paid');
    return $res;
}

public function getStatusShiping(){
    $res = $this->eloquentWhere('status','Shiping');
    return $res;
}

public function getStatusCancel(){
    $res = $this->eloquentWhere('status','Cancel');
    return $res;
}

public function getStatusConfirm(){
    $res = $this->eloquentWhere('status','Confirm');
    return $res;
}

public function getStatusPaidShiping(){
    $res = $this->eloquentWhere('status','PaidShiping');
    return $res;
}

}
