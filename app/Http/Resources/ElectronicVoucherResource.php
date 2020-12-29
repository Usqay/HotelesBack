<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ElectronicVoucherResource extends JsonResource
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
            'date_emitted' => $this->date_emitted,
            'electronic_voucher_type_id' => $this->electronic_voucher_type_id,
            'electronic_voucher_type' => $this->electronic_voucher_type,
            'number' => $this->number,
            'serie' => $this->serie,
            'print' => $this->print,
            'api_body' => $this->api_body,
            'api_response' => $this->api_response,
            'api_state' => $this->api_state,
            'adittional_info' => $this->adittional_info,
            'digits' => $this->digits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
