<?php

namespace App\Services;

use App\Models\OrderDetail;
use App\Models\Variant;

class OrderDetailService extends AbstractServices {
    public function __construct(Variant $cart)
    {
        parent::__construct($cart);
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
        $data = [];
        foreach ($id['data'] as $key => $value){
            $data [] = $this->eloquentWithRelations($value,['attributeName','attributeName.attributeValues','product']);
        }
        return $data;
    }
}
