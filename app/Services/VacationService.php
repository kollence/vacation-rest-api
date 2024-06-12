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

        $this->checkIfDatesOverlap($team->id, $data['start_date'], $data['end_date']);

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

    public function updateVacationRequest($vacationRequest, $data)
    {
        $user = Auth::user();
        $team = $user->team->first();
        if (!$team) {
            throw ValidationException::withMessages(['user' => 'User must belong to a team to request a vacation.']);
        }

        $this->checkIfDatesOverlap($team->id, $data['start_date'], $data['end_date'], $vacationRequest->id);

        $vacationRequest->update([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? null,
        ]);

        return $vacationRequest;
    }

    private function checkIfDatesOverlap($teamId, $startDate, $endDate, $excludeRequestId = null)
    {
        $user = auth()->user();
        $query = VacationRequest::where('team_id', $teamId)
            ->where('status', 'approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<', $startDate)
                            ->where('end_date', '>', $endDate);
                    });
            });

        if ($excludeRequestId) {
            $query->where('id', '!=', $excludeRequestId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages(['date' => 'The selected dates overlap with an existing vacation request.']);
        }
        // Check for exact same start and end date (for creators of the request)
        if (VacationRequest::where('user_id', $user->id)
            ->where('start_date', $startDate)
            ->where('end_date', $endDate)
            ->exists()
        ) {
            throw ValidationException::withMessages(['date' => 'A vacation request with the same start and end date already exists.']);
        }
    }

    public function approveVacationRequest($id, $data)
    {
        $vacationRequest = VacationRequest::findOrFail($id);
        $user = $vacationRequest->user;
        $manager = Auth::user();

        // Ensure the approver is a manager in the same team
        if($manager->isManager() && $manager->team->contains($user->team->first())){
            $vacationDays = $vacationRequest->getVacationDays();

            if($data == 'approved'){
                                // Check if the user has enough vacation days left
                if ($user->vacation_days >= $vacationDays) {
                    // Subtract the vacation days from the user's balance
                    $user->vacation_days -= $vacationDays;
                    $user->save();
                    
                    $vacationRequest->update([
                        'status' => $data,
                        'approved_by' => $manager->id,
                    ]);
                    return $data; //approved
                } else {
                    throw ValidationException::withMessages(['date' => 'User does not have enough vacation days.']);
                }
            }else{
                $vacationRequest->update([
                    'status' => $data,
                    'approved_by' => $manager->id,
                ]);
                return $data; // rejected
            }
            
        }

    }
}
