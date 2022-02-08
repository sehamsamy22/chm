<?php

namespace App\Http\Controllers\Admins;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\FullUserDetailsResource;
use App\Imports\ProductsImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:user-export-excel', ['only' => ['export']]);
        $this->middleware('permission:user-import-excel', ['only' => ['import']]);
    }

    public function index()
    {
        $users = User::all();
        return $this->apiResponse(FullUserDetailsResource::collection($users));
    }

    public function show($id)
    {
        $user = User::with('orders', 'addresses', 'wishes', 'compares', 'cart')->findOrFail($id);
        return $this->apiResponse(new FullUserDetailsResource($user));
    }

    public function export()
    {
        $date = date('Y-m-d-H-i-s');
       Excel::store(new UsersExport, "storage/excels/users/users-{$date}.xlsx", 'public');
        Excel::download(new UsersExport, "users-{$date}.xlsx");

        return $this->apiResponse(['file_url' => asset("storage/excels/users/users-{$date}.xlsx")]);
    }

    public function import(Request $request)
    {
        Excel::import(new ProductsImport(), request()->file('file'));
        return $this->apiResponse(['massage' => "import successfully"]);

    }
}
