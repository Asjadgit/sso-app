<?php

namespace Modules\Filter\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Filter\Http\Requests\StoreCustomFilterRequest;
use Modules\Filter\Models\CustomFilter;
use Modules\VisibilityGroup\Models\VisibilityGroup;
use Modules\VisibilityLevel\Models\VisibilityLevel;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = CustomFilter::with('visibilityLevels')->where('user_id', Auth::id())
            ->get();

        // return response()->json(['filters' => $filters]);
        return view('filters.index', compact('filters'));
    }

    public function create()
    {
        // $visibilityLevels = VisibilityLevel::whereIn('name', ['Private', 'Shared'])->get();
        $visibilityLevels = VisibilityLevel::all();
        $visibilityGroups = VisibilityGroup::all();
        return view('filters.create', compact('visibilityLevels'));
    }
    public function store(StoreCustomFilterRequest $request)
    {

        if (!$request->filled('visibility_level') && !$request->filled('visibility_group_ids')) {
            return response()->json([
                'message' => 'Either a visibility level or at least one visibility group must be selected.',
            ], 422);
        }

        $customFilter = CustomFilter::create([
            'central_user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->entity_type,
            'filter_criteria' => $request->has('filter_criteria') ? json_encode($request->filter_criteria) : [],
        ]);

        // Attach visibility level if provided
        if ($request->filled('visibility_level')) {
            $customFilter->visibilityLevels()->sync([$request->visibility_level]);
        }

        // Attach visibility groups if provided
        if ($request->filled('visibility_group_ids')) {
            $customFilter->visibilityGroups()->sync($request->visibility_group_ids);
        }

        return response()->json([
            'message' => 'Filter Created Successfully!',
            'filter' => $customFilter->load(['visibilityLevels', 'visibilityGroups']), // Load related visibility data
        ], 201);
    }




    public function apiindex($entity_type)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'unauthorized']);
        }

        // Get user's visibility group IDs
        $userVisibilityGroupIds = $user->visibilityGroups()->pluck('visibility_groups.id')->toArray();
        // dd($userVisibilityGroupIds);

        // Fetch filters:
        $filters = CustomFilter::with(['visibilityLevels', 'visibilityGroups'])
            ->where('type', $entity_type)

            // Include filters which are created with Shared visibility
            ->where(function ($query) use ($user, $userVisibilityGroupIds) {
                $query->where('central_user_id', $user->id) // Show filters created by the user
                    ->orWhereHas('visibilityLevels', function ($q) {
                        $q->where('name', 'Shared'); // Filters with shared visibility level
                    })
                    ->orWhereHas('visibilityGroups', function ($q) use ($userVisibilityGroupIds) {
                        $q->whereIn('visibility_groups.id', $userVisibilityGroupIds); // Filters in user's visibility groups
                    });
            })

            // Exclude Private filters not created by the logged in user
            ->whereDoesntHave('visibilityLevels', function ($q) use ($user) {
                $q->where('name', 'Private')->where('custom_filters.central_user_id', '!=', $user->id);
            })

            ->select('name')
            ->get();

        return response()->json(['filters' => $filters]);
    }



    public function destroy(Request $req, $id)
    {
        $customFilter = CustomFilter::findOrFail($id);
        // dd($customFilter->central_user_id);

        // // get the gloab_id of authenticated user
        // $globalId = auth()->user()->global_id;
        // dd($globalId);

        // Ensure only the owner can delete their filter
        if ($customFilter->central_user_id === auth()->user()->id) {
            // Detach visibility levels before deleting
            $customFilter->visibilityLevels()->detach();

            // Delete the custom filter
            $customFilter->delete();

            if ($req->wantsJson()) {
                return response()->json([
                    'message' => 'Filter Deleted Successfully!',
                ], 200);
            }

            return redirect()->route('adminfilters.index');
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }




    }
}
