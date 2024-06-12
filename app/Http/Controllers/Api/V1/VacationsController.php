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
        $user = auth()->user();

        $vacationRequests = VacationRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['vacation_requests' => $vacationRequests]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVacationRequest $request)
    {
        $vacationRequest = $this->vacationService->createVacationRequest($request->validated());

        return response()->json(['message' => 'Vacation request created successfully', 'vacation_request' => $vacationRequest], 201);
    }

    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $vacationRequest = $this->vacationService->approveVacationRequest($id, $validated['status']);

        return response()->json(['message' => 'Vacation request status updated successfully.', 'data' => $vacationRequest], 200);
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
    public function update(UpdateVacationRequest $request, VacationRequest $vacationRequest)
    {
        $vacationRequest = $this->vacationService->updateVacationRequest($vacationRequest, $request->validated());

        return response()->json(['message' => 'Vacation request updated successfully', 'vacationRequest' => $vacationRequest], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VacationRequest $vacationRequest)
    {
        $vacationRequest->delete();

        return response()->json(['message' => 'Vacation request deleted successfully'], 200);
    }
}
