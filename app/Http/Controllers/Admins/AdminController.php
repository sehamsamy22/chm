<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:admin-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $admins = Admin::all();
        return $this->apiResponse(AdminResource::collection($admins));
    }

    public function profile()
    {
        $admin = Auth('admin-api')->user();
        return $this->apiResponse(new AdminResource($admin));
    }

    public function store(AdminRequest $request)
    {
        $admin = Admin::create($request->validated());
        $admin->update(['password' => Hash::make($request['password'])]);
        $admin->syncRoles(request('roles'));
        return $this->apiResponse(new AdminResource($admin));
    }

    public function show($id)
    {
        $admin = Admin::find($id);
        return $this->apiResponse(new AdminResource($admin));
    }

    public function update(AdminRequest $request, $id)
    {
        $admin = Admin::find($id);
        $admin->update($request->validated());
        $admin->syncRoles(request('roles'));
        return $this->apiResponse(new AdminResource($admin));
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return $this->apiResponse((AdminResource::collection(Admin::all())));
    }

    public function setLocale(Request $request)
    {
        $admin = Auth::guard('admin-api')->user();
        $admin->update(['lang' => $request['lang']]);
        return $this->apiResponse(new AdminResource($admin));
    }
}
