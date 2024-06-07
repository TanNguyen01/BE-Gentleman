<?php

namespace App\Services;

use App\Models\Order;

class OrderService extends AbstractServices {
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    public function getAllOrder(){
        return $this->eloquentGetAll();
    }

    public function storeOrder($data){
        return $this->eloquentPostCreate($data);
    }

    public function showOrder($id){
        return $this->eloquentFind($id);
    }

    public function updateOrder($id, $data){
        return $this->eloquentUpdate($id,$data);
    }

    public function destroyOrder($id){
        return $this->eloquentDelete($id);
    }
}
