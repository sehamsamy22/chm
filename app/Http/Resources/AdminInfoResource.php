<?php

namespace App\Http\Resources;

use App\Modules\Permission\Transformers\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => new RoleResource($this->getRoleNames()->first()),
            'email' => $this->email,
            'lang' => $this->lang,
        ];

    }

}
