<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrinterResource extends JsonResource
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
            'port' => $this->port,
            'ip_address' => $this->ip_address,
            'printer_type_id' => $this->printer_type_id,
            'printer_type' => $this->printer_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
