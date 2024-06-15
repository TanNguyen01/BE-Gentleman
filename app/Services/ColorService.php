<?php

namespace App\Services;

use App\Models\Color;


class ColorService extends AbstractServices
{
    public function __construct(Color $color)
    {
        parent::__construct($color);
    }

    public function getAllColor()
    {
        return $this->eloquentGetAll();
    }

    public function storeColor($data)
    {
        return $this->eloquentPostCreate($data);
    }

    public function showColor($id)
    {
        return $this->eloquentFind($id);
    }

    public function updateColor($id, $data)
    {
        $data = (array)$data;
        return $this->eloquentUpdate($id, $data);
    }

    public function destroyColor($id)
    {
        return $this->eloquentDelete($id);
    }
}
