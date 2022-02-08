<?php

namespace App\Http\Resources;

use App\Modules\Blog\Entities\CustomerOpinion;
use Illuminate\Http\Resources\Json\JsonResource;

class OccasionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'name' =>  $this->name,
            'description' => $this->description ,
            'date' => $this->date,
            'type' => $this->type,

        ];
    }
}
