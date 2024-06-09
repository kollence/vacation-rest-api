<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamCollection;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::all(); // Eager load users for each team

        return response()->json([
            'teams' => new TeamCollection($teams->load('users')),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'user_ids' => 'array|distinct', // Ensure at least one unique user ID
            'user_ids.*' => 'exists:users,id', // Validate each user ID exists
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        $team = Team::create([
            'name' => $data['name'],
            'manager_id' => $data['manager_id'],
        ]);
    
        foreach ($data['user_ids'] as $userId) {
            $user = User::find($userId);
            $user->team_id = $team->id;
            $user->save();
        }
    
        return response()->json([
            'message' => 'Team created successfully',
            'team' => $team,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }
}
