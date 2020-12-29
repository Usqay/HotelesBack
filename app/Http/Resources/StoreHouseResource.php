<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreHouseResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'description' => $this->description,
            'is_base' => $this->is_base,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
