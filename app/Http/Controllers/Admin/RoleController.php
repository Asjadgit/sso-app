<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles]);
    }

    public function store(StoreRoleRequest $request) {

        // dd($request->all());
        $role = Role::updateOrCreate(
            ['id' => $request->id], // Check if permission exists by ID
            ['name' => $request->name] // Update name or create new
        );

        if($request->has('permissions')){
            $permissions = Permission::whereIn('id',$request->permissions)->pluck('id')->toArray();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'message' => 'Role saved Successfully',
            'role' => $role->load('permissions'),
        ],201);
    }

    public function destroy($id) {
        $role = Role::find($id);

        $role->update(['guard_name' => 'web']);

        $role->permissions()->detach();

        $role->delete();

        return response()->json([
            'message' => 'Role Deleted Successfully!',
        ],200);
    }
}
