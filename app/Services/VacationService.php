<?php

namespace App\Services;

use App\Models\Vacation;
use App\Models\VacationRequest;

class VacationService
{
    public function createVacationRequest(array $data)
    {
        return VacationRequest::create($data);
    }
}