<?php

namespace App\Http\Resources\Customer;

use App\Modules\Address\Transformers\AddressResource;
use App\Modules\Order\Notifications\NewOrder;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'lang' => $this->lang,
            'created_at' => $this->created_at->format('d-m-Y'),
            'addresses' =>  AddressResource::collection($this->addresses),
        ];
    }
}
