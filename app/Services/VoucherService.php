<?php

namespace App\Services;

use App\Models\Voucher;

class VoucherService extends AbstractServices
{
    public function __construct(Voucher $voucher)
    {
        parent::__construct($voucher);
    }

    public function getAllVoucher()
    {
        return $this->eloquentGetAll();
    }

    public function storeVoucher($data)
    {
        return $this->eloquentPostCreate($data);
    }

    public function showVoucher($id)
    {
        return $this->eloquentFind($id);
    }

    public function updateVoucher($id, $data)
    {
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyVoucher($id)
    {
        return $this->eloquentDelete($id);
    }
}
