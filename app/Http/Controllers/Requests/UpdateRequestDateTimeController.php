<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateRequestDateTimeController extends Controller
{
    public function updateDateTime(Request $request, $id)
    {
        // Find the request service by ID
        $requestService = RequestService::findOrFail($id);

        // Update only the status field
        $requestService->update([
            'status' => $request->input('date_time'),
        ]);

        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'Request datetime updated successfully' , 
        ]);
    }
}
