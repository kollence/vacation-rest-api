<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVacationRequest;
use App\Http\Requests\UpdateVacationRequest;
use App\Models\Team;
use App\Models\User;
use App\Models\VacationRequest;
use App\Services\VacationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class VacationsController extends Controller
{

    public function __construct(
        private VacationService $vacationService
        )
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUserTeam = auth()->user()->team->first();

        $vacationRequests = VacationRequest::where('team_id', $authUserTeam->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['vacation_requests' => $vacationRequests]);
    }

    public function showHistory()
    {
        $authUser = auth()->user();

        $vacationRequests = VacationRequest::where('user_id', $authUser->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Check if the user has the permission to view history
        foreach ($vacationRequests as $vacationRequest) {
            Gate::authorize('viewHistory', $vacationRequest);
        }

        return response()->json(['vacation_requests' => $vacationRequests]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVacationRequest $request)
    {
        Gate::authorize('create', VacationRequest::class);

        $vacationRequest = $this->vacationService->createVacationRequest($request->validated());

        return response()->json(['message' => 'Vacation request created successfully', 'vacation_request' => $vacationRequest], 201);
    }

    public function approve(Request $request, VacationRequest $vacationRequest)
    {
        Gate::authorize('approve', $vacationRequest);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $vacationRequest = $this->vacationService->approveVacationRequest($vacationRequest->id, $validated['status']);

        return response()->json(['message' => 'Vacation request status updated successfully.', 'status' => $vacationRequest], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(VacationRequest $vacationRequest)
    {
        Gate::authorize('view', $vacationRequest);

        return response()->json([
            'vacation_request' => $vacationRequest,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacationRequest $request, VacationRequest $vacationRequest)
    {
        Gate::authorize('update', $vacationRequest);

        $vacationRequest = $this->vacationService->updateVacationRequest($vacationRequest, $request->validated());

        return response()->json(['message' => 'Vacation request updated successfully', 'vacation_request' => $vacationRequest], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VacationRequest $vacationRequest)
    {
        Gate::authorize('delete', $vacationRequest);
        
        $vacationRequest->delete();

        return response()->json(['message' => 'Vacation request deleted successfully'], 200);
    }
}
