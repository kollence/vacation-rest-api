<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamCollection;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TeamsController extends Controller
{
    private $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::with(['managers', 'regularUsers'])->get();

        return response()->json([
            'teams' => new TeamCollection($teams),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTeamRequest $request)
    {

        $team = $this->teamService->createTeam($request->validated());

        return response()->json([
            'team' => new TeamResource($team->load(['managers', 'regularUsers']))
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return response()->json([
            'team' => new TeamResource($team->load(['managers', 'regularUsers'])),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {
        // dd( $request->all());
        $team = $this->teamService->updateTeam($team, $request->all());


        return response()->json([
            'team' => new TeamResource($team->load(['managers', 'regularUsers'])),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        DB::transaction(function () use ($team) {
            $team->delete();
        });

        return response()->json([
            'message' => 'Team deleted successfully',
        ], 200);
    }

    public function removeTeamUser(Request $request, Team $team)
    {

        $validatedData = $request->validate([
            'managers' => 'sometimes|array|min:1',
            'managers.*' => 'exists:users,id',
            'regular_users' => 'sometimes|array|min:1',
            'regular_users.*' => 'exists:users,id',
        ]);
        $team = $this->teamService->removeTeamUser($team, $validatedData);

            return response()->json([
                'team' => new TeamResource($team->load(['managers', 'regularUsers'])),
            ], 200);

    }
}
