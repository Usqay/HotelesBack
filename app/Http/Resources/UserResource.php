<?php

namespace App\Http\Resources;

use App\Models\People;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class UserResource extends JsonResource
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
        $role = $this->getRoleNames()[0];

        $role = Role::findByName($role, 'web');
        $permissions = $this->getAllPermissions();
        $people = People::findOrFail($this->people_id);
        $people = new PeopleResource($people);

        return [
            'id' => $this->id,
            'active' => $active,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $role,
            'people' => $people,
            'permissions' => $permissions,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
