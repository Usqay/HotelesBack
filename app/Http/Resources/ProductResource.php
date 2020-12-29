<?php

namespace App\Http\Resources;

use App\Models\SunatCode;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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

        $productPrices = ProductPriceResource::collection($this->prices);
        $sunatCode = SunatCode::where('code', '=', $this->sunat_code)->first();

        return [
            'id' => $this->id,
            'active' => $active,
            'name' => $this->name,
            'description' => $this->description,
            'sunat_code' => $sunatCode,
            'prices' => $productPrices,
            'stocks' => $this->stocks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
