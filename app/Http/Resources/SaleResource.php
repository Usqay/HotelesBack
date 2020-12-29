<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
        $products = SaleProductResource::collection($this->products);
        $services = SaleServiceResource::collection($this->services);
        $client = new ReservationClientResource($this->client);
        
        return [
            'id' => $this->id,
            'active' => $active,

            'description' => $this->description,
            'client_id' => $this->client_id,
            'client' => $client,
            'coupon_id' => $this->coupon_id,
            'sale_state_id' => $this->sale_state_id,
            'sale_state' => $this->sale_state,
            'room_id' => $this->room_id,
            'room' => $this->room,
            'turn_change_id' => $this->turn_change_id,
            'reservation_id' => $this->reservation_id,
            'products' => $products,
            'services' => $services,
            'totals' => $this->totals,
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
