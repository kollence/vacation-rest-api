<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Models\Vacation;
use App\Models\VacationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VacationService
{
    public function createVacationRequest($data)
    {
        $user = Auth::user();
        $team = $user->team->first();
        if (!$team) {
            throw ValidationException::withMessages(['user' => 'User must belong to a team to request a vacation.']);
        }

        $vacationRequest = VacationRequest::create([
            'user_id' => $user->id,
            'team_id' => $team->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
        ]);

        return $vacationRequest;
    }

}
