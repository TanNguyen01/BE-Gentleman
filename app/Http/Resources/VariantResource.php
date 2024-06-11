<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'quantity' => $this->quantity,
//            'product' => new ProductResource($this->product),
            'attribute_values' => AttributeValueResource::collection($this->attributeValues),
        ];
    }
}
