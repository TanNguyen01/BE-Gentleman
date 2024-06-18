<?php

namespace App\Services;

use App\Models\OrderDetail;

class OrderDetailService extends AbstractServices {
    public function __construct(OrderDetail $orderDetail)
    {
        parent::__construct($orderDetail);
    }

    public function getAllOrderDetail(){
        return $this->eloquentGetAll();
    }

    public function storeOrderDetail($data){
        return $this->eloquentMutiInsert($data);
    }

    public function showOrderDetail($id){
        return $this->eloquentFind($id);
    }

    public function updateOrderDetail($id, $data){
        return $this->eloquentUpdate($id,$data);
    }

    public function destroyOrderDetail($id){
        return $this->eloquentDelete($id);
    }

    public function eloquentOrderDetailWithVariant($id){
        return $this->eloquentWithRelations($id,['variant','variant.attributeName','variant.product']);
    }
}

