<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillService extends AbstractServices
{
    public function __construct(Bill $bill)
    {
        parent::__construct($bill);
    }

    public function getAllBill()
    {
        $bill = $this->eloquentGetAll()->toArray();
        return $this->sortBills($bill);
    }

    public function storeBill($data)
    {
        return $this->eloquentPostCreate($data);
    }

    public function showBill($id)
    {
        return $this->eloquentFind($id);
    }

    public function eloquentBillWithBillDetail($id)
    {
        $relations = ['billDetails'];
        return $this->eloquentWithRelations($id, $relations);
    }

    public function chanceStatusConfirm($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'confirm';
        $res->save();
        return 'confirm';
    }

    public function chanceStatusShipping($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Shipping';
        $res->save();
        return 'Shipping';
    }

    public function chanceStatusPaid($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Paid';
        $res->save();
        return 'Paid';
    }

    public function chanceStatusCancel($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Cancel';
        $res->save();
        return 'Cancel';
    }

    public function chanceStatusPending($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Pending';
        $res->save();
        return 'Pending';
    }

    public function chanceStatusDone($id)
    {
        $res = $this->eloquentFind($id);
        $res->status = 'Done';
        $res->save();
        return 'Done';
    }

    public function getStatusPaid()
    {
        $res = $this->eloquentWhere('status', 'Paid');
        return $res;
    }

    public function getStatusDone()
    {
        $res = $this->eloquentWhere('status', 'Done');
        return $res;
    }

    public function getStatusPending()
    {
        $res = $this->eloquentWhere('status', 'Pending');
        return $res;
    }

    public function getStatusShipping()
    {
        $res = $this->eloquentWhere('status', 'Shipping');
        return $res;
    }

    public function getStatusCancel()
    {
        $res = $this->eloquentWhere('status', 'Cancel');
        return $res;
    }

    public function getStatusConfirm()
    {
        $res = $this->eloquentWhere('status', 'Confirm');
        return $res;
    }

    public function getStatusPaidShipping()
    {
        $res = $this->eloquentWhere('status', 'PaidShipping');
        return $res;
    }

    public function sortBills(array $bills)
    {
        usort($bills, function ($a, $b) {
            $statusOrder = ['Pending', 'Paid', 'Confirm', 'Shipping', 'Done', 'Cancel'];
            $statusA = array_search($a['status'], $statusOrder);
            $statusB = array_search($b['status'], $statusOrder);

            if ($statusA === $statusB) {
                return strtotime($b['updated_at']) - strtotime($a['updated_at']);
            }

            return $statusA - $statusB;
        });

        return $bills;
    }

    public function billWithUser($id)
    {
        $res = $this->eloquentWhere('user_id', $id)->toArray();
        return $this->sortBills($res);
    }

    public function billWithUsePending($id)
    {
        $fill = [
            'user_id' => $id,
            'status' => 'Pending'
        ];
        $res = $this->eloquentMultiWhere($fill);
        return $res;
    }

    public function getBillStoryWithBill($bill_id){
        return $this->eloquentWithRelations($bill_id,['billStory']);
    }

    public function billWithUsePaid($id)
    {
        $fill = [
            'user_id' => $id,
            'status' => 'Paid'
        ];
        $res = $this->eloquentMultiWhere($fill);
        return $res;
    }

    public function billWithUseDone($id)
    {
        $fill = [
            'user_id' => $id,
            'status' => 'Done'
        ];
        $res = $this->eloquentMultiWhere($fill);
        return $res;
    }

    public function billWithUseShipping($id)
    {
        $fill = [
            'user_id' => $id,
            'status' => 'Shipping'
        ];
        $res = $this->eloquentMultiWhere($fill);
        return $res;
    }

    public function billWithUseCancel($id)
    {
        $fill = [
            'user_id' => $id,
            'status' => 'Cancel'
        ];
        $res = $this->eloquentMultiWhere($fill);
        return $res;
    }

    public function billWithUseConfirm($id)
    {
        $fill = [
            'user_id' => $id,
            'status' => 'Confirm'
        ];
        $res = $this->eloquentMultiWhere($fill);
        return $res;
    }

    public function billFinterWithPhone($phone)
    {
        $res = $this->eloquentWhere('recipient_phone', $phone)->toArray();
        return $this->sortBills($res);
    }

    public function billFinterWithEmail($email)
    {
        $res = Bill::whereHas('user', function ($query) use ($email) {
            $query->where('email', $email);
        })->get()->toArray();
        return $this->sortBills($res);
    }
}
