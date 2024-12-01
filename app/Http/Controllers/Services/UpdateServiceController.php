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

class UpdateServiceController extends Controller
{
    public function update(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $user = User::find($user_id); 
        
        $service_provider_id = ServiceProvider::where("user_id" , $user_id)->pluck("id")[0];

        $validatedData = $request->validate([
            'serviceprovider_id' => ['required', 'numeric', 'exists:service_providers,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $service = Service::findOrFail($request->id);

        $fileName = '';
        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::delete('public/images/services/' . $service->image);
            }
            $fileName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/images/services', $fileName);
        }

        $service->update([
            'serviceprovider_id' => $service_provider_id,
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'image' => $fileName ?? null,
        ]);

        return response()->json([
            'status' => true,
            'service' => $service,
        ]);

    }
}
