<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ServiceProvider;
use App\Models\Service;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;


class CreateServiceController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->bearerToken();
    
        $decoded = JWTAuth::setToken($token)->getPayload();
    
        $user_id = $decoded['sub'];
    
        $user = User::find($user_id); 
        
        $service_provider_id = ServiceProvider::where("user_id" , $user_id)->pluck("id")[0];
        
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'type' => ['required', 'numeric'],
                'duration_hours' => ['required', 'integer', 'min:0'],
                'duration_minutes' => ['required', 'integer', 'min:0', 'max:59'],
            ]);
    
            // Handle image upload
            $fileName = null;
            if ($request->hasFile('image')) {
                $fileName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/images/services', $fileName);
            }
    
            $service = Service::create([
                'serviceprovider_id' => $service_provider_id,
                'name' => $validatedData['name'],
                'image' => $fileName ?? "",
                'price' => $validatedData['price'],
                'type' => $validatedData['type'],
                'duration_hours' => $validatedData['duration_hours'],
                'duration_minutes' => $validatedData['duration_minutes'],
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Service added successfully',
                'service' => $service,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        }    
    }
}
