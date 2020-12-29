<?php

namespace App\Http\Resources;

use App\Models\SunatCode;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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

        $servicePrices = ServicePriceResource::collection($this->prices);
        $products = ServiceProductResource::collection($this->products);
        $sunatCode = SunatCode::where('code', '=', $this->sunat_code)->first();

        return [
            'id' => $this->id,
            'active' => $active,
            'name' => $this->name,
            'description' => $this->description,
            'sunat_code' => $sunatCode,
            'prices' => $servicePrices,
            'products' => $products,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
