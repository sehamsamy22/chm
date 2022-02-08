<?php
namespace App\Modules\Permission\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'slug' => $this->slug,
            'permissions' => PermissionResource::collection($this->permissions->groupBy('order_by'))->values()
//            'permissions' => $this->permissions->groupBy('order_by')->map(function ($permissions, $module) {
//                $moduleName = substr($module, 0, -6);
//                $moduleSlug = collect(config('permission_modules'))->where('name', $moduleName)->first()['slug'];
//                return [
//                    'module_name' => $moduleName,
//                    'module_name_ar' => $moduleSlug,
//                    'permissions' => PermissionResource::collection($permissions)
//
//                ];
//            })->values(),

        ];

    }

}
