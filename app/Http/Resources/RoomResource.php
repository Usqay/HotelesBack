<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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

        $roomCategory = new RoomCategoryResource($this->room_category);
        $roomStatus = new RoomStatusResource($this->room_status);
        $roomPrices = RoomPriceResource::collection($this->room_prices);
        $roomProducts = RoomProductResource::collection($this->room_products);

        return [
            'id' => $this->id,
            'active' => $active,
            'name' => $this->name,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'room_category' => $roomCategory,
            'room_status' => $roomStatus,
            'room_category_id' => $this->room_category_id,
            'room_status_id' => $this->room_status_id,
            'room_prices' => $roomPrices,
            'room_products' => $roomProducts,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
