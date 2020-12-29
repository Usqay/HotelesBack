<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreHouseMovementResource extends JsonResource
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

        $products = ProductMovementResource::collection($this->products);

        return [
            'id' => $this->id,
            'active' => $active,
            'store_house_id' => $this->store_house_id,
            'store_house_movement_type_id' => $this->store_house_movement_type_id,
            'store_house_movement_type' => $this->store_house_movement_type,
            'description' => $this->description,
            'products' => $products,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}