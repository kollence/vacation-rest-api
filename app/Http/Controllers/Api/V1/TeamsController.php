<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamResource;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamCollection;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    /** 
     * JUST FOR ADMIN !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     * 
     * 
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::with('users')->get();

        return response()->json([
            'teams' => new TeamCollection($teams),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTeamResource $request)
    {
        $team = DB::transaction(function () use ($request) {
            $team = Team::create([
                'name' => $request['name'],
                'manager_id' => $request['manager_id'],
            ]);

            $user_ids = $request['user_ids'];
            if (isset($user_ids)) {
                User::whereIn('id', $user_ids)->update(['team_id' => $team->id]);
            }
            return $team;
            
        });
        
        return response()->json([
                'team' =>  new TeamResource($team->load('users')),
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return response()->json([
            'team' => new TeamResource($team->load('users')),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team)
    {

        $data = $request->validated();
        DB::transaction(function () use ($team, $data) {
            $team->update($data);

            if (isset($data['user_ids'])) {
                User::whereIn('id', $data['user_ids'])->update(['team_id' => $team->id]);
            }
        });
        
    
        return response()->json([
            'team' => new TeamResource($team->load('users')),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        DB::transaction(function () use ($team) {
            $team->users()->update(['team_id' => null]);
            $team->delete();
        });
    
        return response()->json([
            'message' => 'Team deleted successfully',
        ], 200);
    }

    public function removeTeamUser(Request $request, Team $team)
    {

        $validator = Validator::make($request->all(), [
            'user_ids' => 'array|distinct', // Ensure at least one unique user ID
            'user_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        if(isset($request['user_ids']) && count($request['user_ids']) > 0){
            $team->users()->whereIn('id', $request['user_ids'])->update(['team_id' => null]);
            return response()->json([
                'message' => 'Team users successfully deleted',
            ], 200);

        }else{
            return response()->json([
                'message' => 'Team users are not selected',
            ], 200);
        }
    }
}
