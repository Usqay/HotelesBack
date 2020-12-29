<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductMovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $active = $this->deleted_at == null ? true : false;

        return [
            'id' => $this->id,
            'active' => $active,
            'product_id' => $this->product_id,
            'store_house_movement_id' => $this->store_house_movement_id,
            'product_movement_type_id' => $this->product_movement_type_id,
            'quantity' => $this->quantity,
            'product' => $this->product,
            'product_movement_type' => $this->product_movement_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
