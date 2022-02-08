<?php

namespace App\Modules\Permission\Http\Controllers\Admin;

use App\Http\Resources\AdminInfoResource;
use App\Http\Resources\Customer\UserResource;
use App\Models\Admin;
use App\Modules\Permission\Transformers\PermissionResource;
use App\Modules\Permission\Transformers\RoleResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list', ['only' => ['index']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        return $this->apiResponse(RoleResource::collection($roles));
    }

    public function allPermissions()
    {
        $permissions = Permission::all();
        return $this->apiResponse(PermissionResource::collection($permissions->groupBy('order_by'))->values());
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'slug' => 'required|unique:roles,slug',
            'permission' => 'required'
        ], [
            'slug.required' => 'فضلا أكتب اسم الصلاحيه',
            'permission.required' => 'فضلا أختر الصلاحيات'
        ]);
        $role = Role::create([
            'guard_name' => 'admin-api',
            'name' => "role-".time(),
            'slug' => $request->slug
        ]);
        $role->syncPermissions($request->input('permission'));
        return $this->apiResponse(['roles' => new RoleResource($role)]);

    }

    public function show($id)
    {
        $role = Role::with('permissions')->find($id);
        return $this->apiResponse(['roles' => new RoleResource($role)]);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'slug' => 'required',
            'permission' => 'required',
        ], [
            'slug.required' => 'فضلا أكتب اسم الصلاحيه',
            'permission.required' => 'فضلا أختر الصلاحيات'
        ]);
        $role = Role::find($id);
        $role->slug = $request->slug;
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return $this->apiResponse(['roles' => new RoleResource($role)]);

    }

    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        DB::table("model_has_roles")->where('role_id', $id)->delete();
        return $this->apiResponse(RoleResource::collection(Role::all()));
    }

    public function AdminsHasRole ($roleName)
    {
        $admins= Admin::role($roleName)->get();
        return $this->apiResponse(AdminInfoResource::collection($admins));
    }
}
