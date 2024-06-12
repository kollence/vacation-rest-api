<?php

namespace App\Services;

use App\Models\Vacation;
use App\Models\VacationRequest;

class VacationService
{


    public function createVacationRequest(array $data)
    {
        return VacationRequest::create([
            'user_id' => $data['user_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => 'pending',
            'reason' => $data['reason'] ?? null,
        ]);
    }
}