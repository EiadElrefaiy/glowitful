<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestService;

class DeleteStatusController extends Controller
{
    public function destroy($id)
    {
        // Find the request service by ID
        $requestService = RequestService::findOrFail($id);

        // Delete the request service
        $requestService->delete();

        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'Request deleted successfully' , 
        ]);
    }
}
