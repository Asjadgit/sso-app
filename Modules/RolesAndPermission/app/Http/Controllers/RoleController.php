<?php

namespace Modules\RolesAndPermission\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rolesandpermission::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request) {
        $role = Role::updateOrCreate(['name' => $request->name]);

        if($request->has('permissions')){
            $permissions = Permission::whereIn('id',$request->permissions)->pluck('id')->toArray();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'message' => 'Role Created Successfully',
            'role' => $role->load('permissions'),
        ],201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('rolesandpermission::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('rolesandpermission::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
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
