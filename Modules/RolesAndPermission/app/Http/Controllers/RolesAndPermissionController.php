<?php

namespace Modules\RolesAndPermission\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json([
            'permissions' => $permissions,
        ], 200);
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
    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::updateOrCreate(['name' => $request->name]);

        return response()->json([
            'message' => 'Permission saved successfully!',
            'permission' => $permission
        ], 200);
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
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        $permission->update(['guard_name' => 'web']);

        $permission->delete();

        return response()->json([
            'message' => 'Permission Deleted Sucessfully!'
        ], 200);
    }
}
