<?php

namespace App\Modules\Setting\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
                'trans_name' => __("settings.{$this->module}.{$this->name}"),
                'name' => $this->name,
                'value' => $this->value,
                "type" => $this->type,
                "module" => $this->module,
                "module_ar" => __("settings.{$this->module}.page_title"),
                "order_by" => $this->order_by
        ];
    }
}
