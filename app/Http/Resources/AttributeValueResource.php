<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'attribute_id' => $this->attribute_id,
            'value' => $this->value,
//            'attribute' => new AttributeResource($this->attribute),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
