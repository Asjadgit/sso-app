<?php

namespace Modules\VisibilityLevel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VisibilityLevel\Http\Requests\StoreVisibilityLevelRequest;
use Modules\VisibilityLevel\Models\VisibilityLevel;

class VisibilityLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $visibilityLevels = VisibilityLevel::all();
        if ($req->wantsJson()) {
            return response()->json([
                'Visibility Levels' => $visibilityLevels,
            ], 201);
        }
        // return view('visibility-groups.visibility-levels.index', compact('visibilityLevels'));
    }

    public function create()
    {
        // return view('visibility-groups.visibility-levels.create');
    }

    public function store(StoreVisibilityLevelRequest  $request)
    {
        $VisibilityLevel = VisibilityLevel::create($request->all());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Visibility Level Created Successfully',
                'Visibility Level' => $VisibilityLevel,
            ], 201);
        }

        // return redirect()->route('visibility_levels.index')->with('success', 'Visibility Level created successfully.');
    }

    public function edit(Request $request, VisibilityLevel $visibilityLevel)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'Visibility Level' => $visibilityLevel,
            ], 201);
        }
        return view('visibility-groups.visibility-levels.edit', compact('visibilityLevel'));
    }

    public function update(Request $request, VisibilityLevel $visibilityLevel)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:visibility_levels,name,' . $visibilityLevel->id,
            'description' => 'nullable|string',
        ]);

        $visibilityLevel->update($request->all());

        return redirect()->route('visibility_levels.index')->with('success', 'Visibility Level updated successfully.');
    }

    public function destroy(VisibilityLevel $visibilityLevel)
    {
        $visibilityLevel->delete();
        return redirect()->route('visibility_levels.index')->with('success', 'Visibility Level deleted successfully.');
    }


    public function apiedit(Request $request, $id)
    {
        $visibilityLevel = VisibilityLevel::findOrFail($id); // Ensures data exists

        if ($request->wantsJson()) {
            return response()->json([
                'visibility_level' => $visibilityLevel, // Proper JSON key
            ], 200);
        }

        return view('visibility-groups.visibility-levels.edit', compact('visibilityLevel'));
    }

    public function apiupdate(StoreVisibilityLevelRequest $request, $id)
    {
        $visibilityLevel = VisibilityLevel::findOrFail($id); // Ensures data exists

        $visibilityLevel->update($request->all());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Level Updated Successfully',
                'visibility_level' => $visibilityLevel, // Proper JSON key
            ], 200);
        }

        // return redirect()->route('visibility_levels.index')->with('success', 'Visibility Level updated successfully.');
    }

    public function apidestroy(Request $request, $id)
    {
        $visibilityLevel = VisibilityLevel::findOrFail($id); // Ensures data exists
        $visibilityLevel->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Level Deleted Successfully',
            ], 200);
        }
        // return redirect()->route('visibility_levels.index')->with('success', 'Visibility Level deleted successfully.');
    }
}
