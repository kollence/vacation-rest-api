<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Models\VacationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VacationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        if (!$user->team->first()) {
            throw ValidationException::withMessages(['team' => 'You are not part of any team.']);
        }

        $team = Auth::user()->team->first();

        $vacationRequest = VacationRequest::create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Vacation request created successfully.', 'data' => $vacationRequest], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(VacationRequest $vacationRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VacationRequest $vacationRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VacationRequest $vacationRequest)
    {
        //
    }
}
