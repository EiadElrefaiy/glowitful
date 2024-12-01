<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\RequestService;
use Illuminate\Validation\ValidationException;

class CreateRequestController extends Controller
{
    public function create(Request $request)
    {
        try {

        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        // Validate the incoming request data
        $request->validate([
            'serviceprovider_id' => ['required' , 'exists:service_providers,id'],
            'date_time' => ['required' , 'string'],
            'name' => ['required' , 'string'],
            'price' => ['required' , 'numeric'],
            'status' => ['required' , 'string'],
        ]);

        // Create a new request service
        $requestService = RequestService::create([
            'user_id' => $user_id,
            'serviceprovider_id' => $request->input('serviceprovider_id'),
            'date_time' => $request->input('date_time'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'status' => $request->input('status'),
        ]);


        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'Request created successfully' , 
            'requestService' => $requestService,
        ]);
        }
        catch (ValidationException $e) {
        // Return JSON response for validation errors
        return response()->json([
            'status' => false,
            'errors' => $e->errors()], 422);
     }    
  }
}
