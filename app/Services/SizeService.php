<?php

namespace App\Services;

use App\Models\Size;


class SizeService extends AbstractServices
{
    public function __construct(Size $size)
    {
        parent::__construct($size);
    }

    public function getAllSize()
    {
        return $this->eloquentGetAll();
    }

    public function storeSize($data)
    {
        return $this->eloquentPostCreate($data);
    }

    public function showSize($id)
    {
        return $this->eloquentFind($id);
    }

    public function updateSize($id, $data)
    {
        $data = (array)$data;
        return $this->eloquentUpdate($id, $data);
    }

    public function destroySize($id)
    {
        return $this->eloquentDelete($id);
    }
}
