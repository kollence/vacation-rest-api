<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'vacation_days' => $this->vacation_days,
            'vacation_requests' => VacationRequestResource::collection( $this->whenLoaded('vacationRequests')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ];
    }
}
