<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use App\Jobs\SendMail;
use App\Mail\BillConfirmationMail;
use App\Models\Order;

use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderService extends AbstractServices
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    public function getAllOrder()
    {
        return $this->eloquentGetAll();
    }

    public function storeOrder($data)
    {
        return $this->eloquentPostCreate($data);
    }






    public function showOrder($id)
    {
        return $this->eloquentFind($id);
    }

    public function updateOrder($id, $data)
    {
        $data = (array)$data;
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyOrder($id)
    {
        return $this->eloquentDelete($id);
    }

    public function eloquentOrderWithOrderDetail($id)
    {
        $relations = ['orderDetails'];
        return $this->eloquentWithRelations($id, $relations);
    }

    public function chanceStatusConfirm($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'confirm';
        $res->save();
        return 'confirm';
    }

    public function chanceStatusShiping($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Shiping';
        $res->save();
        return 'confirm';
    }

    public function chanceStatusPaid($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Paid';
        $res->save();
        return 'confirm';
    }

    public function chanceStatusPaidShiping($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'PaidShiping';
        $res->save();
        return 'confirm';
    }

    public function chanceStatusCancel($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Cancel';
        $res->save();
        return 'confirm';
    }



}
