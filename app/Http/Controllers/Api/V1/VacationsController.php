<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVacationRequest;
use App\Http\Requests\UpdateVacationRequest;
use App\Http\Resources\UserVacationRequestResource;
use App\Http\Resources\VacationRequestCollection;
use App\Http\Resources\VacationRequestResource;
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
        Gate::authorize('viewAny', VacationRequest::class);

        $vacationRequests = VacationRequest::with('user')->get();
        
        return response()->json(['vacation_requests_with_users' => new VacationRequestCollection($vacationRequests)]);
    }

    public function showHistory()
    {
        $user = auth()->user();

        $vacationRequests = $user->load('vacationRequests');
            
        // Check if the user has the permission to view history
        foreach ($vacationRequests['vacationRequests'] as $vacationRequest) {
            Gate::authorize('viewHistory', $vacationRequest);
        }

        return response()->json(['user_history' => new UserVacationRequestResource($vacationRequests)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVacationRequest $request)
    {
        Gate::authorize('create', VacationRequest::class);

        $vacationRequest = $this->vacationService->createVacationRequest($request->validated());

        return response()->json(['message' => 'Vacation request created successfully', 'vacation_request' => new VacationRequestResource($vacationRequest)], 201);
    }

    public function approve(Request $request, VacationRequest $vacationRequest)
    {
        Gate::authorize('approve', $vacationRequest);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $result = $this->vacationService->approveVacationRequest($vacationRequest->id, $validated['status']);

        return response()->json(['message' => 'Vacation request status updated successfully.', 'status' => $result], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(VacationRequest $vacationRequest)
    {
        Gate::authorize('view', $vacationRequest);

        return response()->json([
            'vacation_request' => new VacationRequestResource($vacationRequest->load('user')),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacationRequest $request, VacationRequest $vacationRequest)
    {
        Gate::authorize('update', $vacationRequest);

        $vacationRequest = $this->vacationService->updateVacationRequest($vacationRequest, $request->validated());

        return response()->json(['message' => 'Vacation request updated successfully', 'vacation_request' => new VacationRequestResource($vacationRequest)], 200);
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
