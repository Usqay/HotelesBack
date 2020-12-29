<?php

namespace App\Http\Resources;

use App\Models\CurrencyRate;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
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

        $rate = CurrencyRate::where('currency_id', '=', $this->id)
        ->where('rate_date', '=', date('Y-m-d'))->first();

        return [
            'id' => $this->id,
            'active' => $active,
            'name' => $this->name,
            'plural_name' => $this->plural_name,
            'symbol' => $this->symbol,
            'code' => $this->code,
            'rate' => $rate,
            'is_base' => $this->is_base,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
