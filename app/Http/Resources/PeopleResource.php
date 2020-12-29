<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PeopleResource extends JsonResource
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
        $gender = new GenderResource($this->gender);
        $document_type = new DocumentTypeResource($this->document_type);

        return [
            'id' => $this->id,
            'active' => $active,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender_id' => $this->gender_id,
            'gender' => $gender,
            'document_type_id' => $this->document_type_id,
            'document_type' => $document_type,
            'document_number' => $this->document_number,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'birthday_date' => $this->birthday_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
