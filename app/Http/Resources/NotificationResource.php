<?php

namespace App\Http\Resources;

use App\Modules\Permission\Transformers\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'data' =>[
                'title'=>$request->header('Content-language') =='ar'?$this->data['title']['ar']:$this->data['title']['en'],
                'body'=>$request->header('Content-language') =='ar'?$this->data['body']['ar']:$this->data['body']['en']
            ],
            'created_at' => $this->created_at->format('d-m-Y'),
        ];

    }

}
