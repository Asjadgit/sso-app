<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json([
            'permissions' => $permissions,
        ], 200);
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::updateOrCreate(
            ['id' => $request->id], // Check if permission exists by ID
            ['name' => $request->name] // Update name or create new
        );

        return response()->json([
            'message' => 'Permission saved successfully!',
            'permission' => $permission
        ], 200);
    }

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
