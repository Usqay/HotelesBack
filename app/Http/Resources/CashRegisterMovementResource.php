<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CashRegisterMovementResource extends JsonResource
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
            'currency_id' => $this->currency_id,
            'currency' => $this->currency,
            'cash_register_movement_type_id' => $this->cash_register_movement_type_id,
            'cash_register_movement_type' => $this->cash_register_movement_type,
            'cash_register_id' => $this->cash_register_id,
            'cash_register' => $this->cash_register,
            'turn_change_id' => $this->turn_change_id,
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'description' => $this->description,
            'additional_info' => $this->additional_info,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
