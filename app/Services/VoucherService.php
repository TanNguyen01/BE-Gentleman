<?php

namespace App\Services;

use App\Models\Voucher;

class VoucherService extends AbstractServices
{
    public function __construct(Voucher $voucher)
    {
        parent::__construct($voucher);
    }
}
