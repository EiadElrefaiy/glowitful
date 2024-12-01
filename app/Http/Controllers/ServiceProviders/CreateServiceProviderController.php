<?php

namespace App\Http\Controllers\ServiceProviders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;


class CreateServiceProviderController extends Controller
{
    public function store(Request $request)
    {
        try {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $user = User::find($user_id); 

        $validatedData = $request->validate([
            'location_lat' => ['required', 'numeric'],
            'location_long' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'review' => ['required', 'numeric'],
            'type' => ['required', 'string', 'max:255'],
            ]);

        $serviceProvider =  ServiceProvider::create([
            'user_id' => $user_id,
            'location_lat' => $validatedData['location_lat'],
            'location_long' => $validatedData['location_long'],
            'address' => $validatedData['address'],
            'review' => $validatedData['review'],
            'type' => $validatedData['type']
        ]);

        return response()->json([
            'status' => true,
            'message' => 'ServiceProvider added successfully' , 
            'serviceProvider' => $serviceProvider,
        ]);
        } catch (ValidationException $e) {
            // Return JSON response for validation errors
            return response()->json([
                'status' => false,
                'errors' => $e->errors()], 422);
        }    
    }

}
