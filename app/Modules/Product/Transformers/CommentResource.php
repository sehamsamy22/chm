<?php

namespace App\Modules\Product\Transformers;

use App\Http\Resources\Customer\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user' => new UserResource($this->user_id),
            'comment' => $this->pivot->comment,
        ];
    }
}
