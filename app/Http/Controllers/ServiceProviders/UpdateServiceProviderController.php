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

class UpdateServiceProviderController extends Controller
{
    public function update(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $id = ServiceProvider::where("user_id" , $user_id)->pluck("id")[0];

        $validatedData = $request->validate([
            'location_lat' => ['required', 'numeric'],
            'location_long' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'review' => ['required', 'numeric'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        $serviceProvider = ServiceProvider::findOrFail($id);
        
        $serviceProvider -> update([
            'user_id' => $user_id,
            'location_lat' => $validatedData['location_lat'],
            'location_long' => $validatedData['location_long'],
            'address' => $validatedData['address'],
            'review' => $validatedData['review'],
            'type' => $validatedData['type']
        ]);

        return response()->json([
            'status' => true,
            'message' => 'ServiceProvider updated successfully' , 
            'serviceProvider' => $serviceProvider,
        ]);
    }
}
