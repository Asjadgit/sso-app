<?php

namespace Modules\VisibilityGroup\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use Illuminate\Http\Request;
use Modules\VisibilityGroup\Http\Requests\AddUserToGroupRequest;
use Modules\VisibilityGroup\Http\Requests\StoreVisibilityGroupRequest;
use Modules\VisibilityGroup\Models\VisibilityGroup;
use Modules\VisibilityLevel\Models\VisibilityLevel;

class VisibilityGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groups = VisibilityGroup::with(['parent', 'children', 'users'])->get();

        if ($request->wantsJson()) {
            return response()->json(['groups' => $groups], 201);
        }

        $visibilityLevels = VisibilityLevel::all(); // Get all visibility levels

        return view('visibility-groups.index', compact('groups', 'visibilityLevels'));
    }



    public function create(Request $request)
    {
        $visibilityGroups = VisibilityGroup::with('users')->get();

        // If a parent_id is provided, load sub-group creation form
        if ($request->has('parent_id')) {
            $parentGroup = VisibilityGroup::findOrFail($request->parent_id);
            return view('visibility-groups.create-sub', compact('parentGroup'));
        }

        // Otherwise, load the main group creation form
        return view('visibility-groups.create', compact('visibilityGroups'));
    }


    public function store(StoreVisibilityGroupRequest $request)
    {

        $group = VisibilityGroup::create($request->all());

        // Retrieve all permissions
        $groups = VisibilityGroup::all();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Group created successfully.',
                'group' => $group,
            ], 201);
        }
    }

    public function show(Request $request, string $id)
    {
        $users = CentralUser::all();
        $group = VisibilityGroup::with(['users', 'parent'])->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'group' => $group,
            ], 201);
        }

        return view('visibility-groups.show', compact('group', 'users'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $group = VisibilityGroup::with(['parent', 'children'])->findOrFail($id);
        $visibilityGroups = VisibilityGroup::where('id', '!=', $id)->get(); // Exclude current group from parent selection

        if ($request->wantsJson()) {
            return response()->json([
                'group' => $group,
                'visibilityGroups' => $visibilityGroups
            ], 200);
        } else {
            return view('visibility-groups.edit', compact('group', 'visibilityGroups'));
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVisibilityGroupRequest $request, $id)
    {

        // Find the group or fail
        $group = VisibilityGroup::findOrFail($id);

        // Update the group details
        $group->update($request->all());

        // Handle JSON and Redirect Responses
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Group updated successfully.',
                'group' => $group->load('users'),
            ], 200);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $group = VisibilityGroup::find($id);

            // Now safely delete the group
            $group->delete();
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Group deleted successfully.',
                ], 201);
            }

            return redirect()->route('visibility_groups.index')->with('success', 'Group deleted successfully.');
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function addUser(AddUserToGroupRequest $request, $id)
    {
        $group = VisibilityGroup::findOrFail($id);

        // Attach users to the group (without duplicates)
        $group->users()->syncWithoutDetaching($request->validated()['user_id']);

        // Reload the group with the latest user associations
        $group->load('users');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User Assigned Successfully',
                'group' => $group
            ]);
        }

        // return redirect()->route('visibility_groups.show', $group->id)
        //     ->with('success', 'Users added successfully');
    }

    public function removeUser(Request $request, $groupId, $userId)
    {
        $group = VisibilityGroup::findOrFail($groupId);
        $user = CentralUser::findOrFail($userId);

        // Detach the user from the group
        $group->users()->detach($userId);

        // Reload the group to reflect changes
        $group->load('users');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User removed successfully',
                'group' => $group
            ]);
        }

        return redirect()->route('visibility_groups.show', $groupId)->with('success', 'User removed successfully');
    }

    public function removeMultipleUsers(Request $request, $groupId)
    {
        $group = VisibilityGroup::findOrFail($groupId);

        // Validate that 'user_ids' is an array and contains existing users
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Detach selected users from the group
        $group->users()->detach($request->user_ids);

        return redirect()->route('visibility_groups.show', $group->id)
            ->with('success', 'Selected users removed successfully');
    }

    public function assignLevel(Request $request)
    {
        $request->validate([
            'visibility_group_id' => 'required|exists:visibility_groups,id',
            'visibility_level_id' => 'required|exists:visibility_levels,id',
        ]);

        $group = VisibilityGroup::find($request->visibility_group_id);
        $group->visibilityLevels()->attach($request->visibility_level_id);

        return response()->json(['message' => 'Visibility Level assigned successfully!']);
    }
}
