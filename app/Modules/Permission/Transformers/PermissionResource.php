<?php
namespace App\Modules\Permission\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $moduleName = substr($this[0]->order_by, 0, -6);
        $moduleSlug = collect(config('permission_modules'))->where('name', $moduleName)->first()['slug'];
        return [
            'module_name' => $moduleName,
            'slug' => $moduleSlug,
            'permissions' => $this->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'slug' => $permission->slug
                ];
            })
        ];
    }
}
