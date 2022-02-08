<?php

namespace App\Modules\Payment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name'),
            'image' => $this->image,
            'is_online' => $this->is_online,
            'status' => (bool)$this->deactivated_at,
            'credentials' => $this->credentials
        ];
    }
}
