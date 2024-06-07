<?php

namespace App\Services;

use App\Models\Order;

class OrderService {

       public function getAllOrder() {

           return Order::query()->get();
       }

       public function getOrderById($id) {
           return Order::with('user')->find($id);
       }

}
