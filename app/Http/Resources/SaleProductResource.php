<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleProductResource extends JsonResource
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

            'sale_id' => $this->sale_id,
            'product_id' => $this->product_id,
            'product' => $this->product,
            'currency' => $this->currency,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'rate_value' => $this->rate_value,
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
