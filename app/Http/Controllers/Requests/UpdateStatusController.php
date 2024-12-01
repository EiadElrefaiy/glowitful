<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestService;

class UpdateStatusController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'status' => ['required' , 'numeric'],
        ]);

        // Find the request service by ID
        $requestService = RequestService::findOrFail($id);

        // Update only the status field
        $requestService->update([
            'status' => $request->input('status'),
        ]);

        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'Request status updated successfully' , 
        ]);
    }
}
