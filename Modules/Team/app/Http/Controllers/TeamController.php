<?php

namespace Modules\Team\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Team\Http\Requests\StoreTeamRequest;
use Modules\Team\Models\Team;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'active'); // Default to 'active' if no status is selected

        $teams = Team::with(['manager', 'members'])
            ->where('status', $status)
            ->get();

        if ($request->wantsJson()) {
            return response()->json(['teams' => $teams]);
        }

        return view('superadmin.team.index', compact('teams', 'status'));
    }


    public function create()
    {
        $users = CentralUser::all();
        return view('superadmin.team.create', compact('users'));
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create([
            'name' => $request->name,
            'manager_id' => $request->manager_id,
            'description' => $request->description,
            'status' => 'active',
        ]);

        // Attach members to the team
        $team->members()->sync($request->members);

        // Load members for the response
        $team->load('members');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Team created successfully',
                'team' => $team
            ], 201);
        }

        // return redirect()->route('teams.index')->with('success', 'Team Created Successfully');
    }

    public function show(Request $request, $id)
    {
        $team = Team::with(['manager', 'members'])->find($id);

        if ($request->wantsJson()) {
            return response()->json(
                [
                    'message' => 'Team Details',
                    'team' => $team,
                ]

            );
        }

        return view('superadmin.team.show', compact('team'));
    }


    public function edit(Request $request, $id)
    {

        $team = Team::with(['manager', 'members'])->find($id);
        $users = CentralUser::all();

        if ($request->wantsJson()) {
            return response()->json(
                [
                    'message' => 'Team Details',
                    'team' => $team,
                ]

            );
        }

        return view('superadmin.team.edit', compact('team', 'users'));
    }


    public function update(StoreTeamRequest $request, $id)
    {

        $team = Team::find($id);

        $team->update([
            'name' => $request->name,
            'manager_id' => $request->manager_id,
            'description' => $request->description,
            'status' => 'active',
        ]);

        $team->members()->sync($request->members);

        $team->with('members');

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Team Updated successfully', 'team' => $team]);
        }

        // return redirect()->route('teams.index')->with('success', 'Team Updated Successfully');
    }

    public function destroy(Request $request, $id)
    {
        $team = Team::where('id', $id)->where('status', 'inactive')->first();

        if (!$team) {
            return redirect()->route('teams.index')->with('error', 'Only inactive teams can be deleted!');
        }

        $team->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Team deleted successfully']);
        }

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $team = Team::find($id);

        if ($team) {
            // Toggle status
            $newStatus = $team->status === 'active' ? 'inactive' : 'active';

            $team->update([
                'status' => $newStatus,
            ]);

            $message = $newStatus === 'active' ? 'Team Reactivated Successfully!' : 'Team Deactivated Successfully!';
        } else {
            $message = 'Team not found!';
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => $message]);
        }

        // return redirect()->route('teams.index')->with('success', $message);
    }
}
