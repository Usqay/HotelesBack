<?php

namespace App\Http\Resources;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationRoomResource extends JsonResource
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
        $room = $this->room;
        $room->room_category = RoomCategory::findOrFail($room->room_category_id);

        return [
            'id' => $this->id,
            'active' => $active,

            'reservation_id' => $this->reservation_id,
            'room_id' => $this->room_id,
            'room' => $this->room,
            'currency_id' => $this->currency_id,
            'currency' => $this->currency,
            'price_type' => $this->price_type,
            'price_value' => $this->price_value,
            'total_price' => $this->total_price,
            'rate_value' => $this->rate_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
