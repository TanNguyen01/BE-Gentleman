<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class SaleService extends AbstractServices
{
    public function __construct(Sale $sale)
    {
        parent::__construct($sale);
    }

    public function getAllSale()
    {
        return $this->eloquentGetAll();
    }

    public function storeSale($sale)
    {
        return $this->eloquentPostCreate($sale);
    }

    public function showSale($id)
    {
        return $this->eloquentFind($id);
    }

    public function updateSale($id, $sale)
    {
        $sale = (array)$sale;
        return $this->eloquentUpdate($id, $sale);
    }

    public function destroySale($id)
    {
        return $this->eloquentDelete($id);
    }

    public function onLayoutSale(){
        $res = $this->eloquentGetAll();
        $data = [];
        foreach ($res as $key => $value) {
            if($value->onLayout == 1 && $value->status == 1){
                $data[] = $res[$key];
            }
        }
        return $data;
    }

    public function EloquentSaleWithProduct($id){
        return $this->eloquentWithRelations($id,['product']);
    }
}