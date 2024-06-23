<?php

namespace App\Services;

use App\Models\BillStory;

class BillStoryService extends AbstractServices {
    public function __construct(BillStory $billStory)
    {
        parent::__construct($billStory);
    }

    public function getAllBillStory(){
        return $this->eloquentGetAll();
    }

    public function storeBillStory($data){
        return $this->eloquentPostCreate($data);
    }

    public function showBillStory($id){
        return $this->eloquentFind($id);
    }

}
