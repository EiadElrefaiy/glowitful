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

class ReadServiceProviderController extends Controller
{
    public function show(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $id = ServiceProvider::where("user_id" , $user_id)->pluck("id")[0];

        $serviceProvider = ServiceProvider::with("Services")->findOrFail($id);
        
        return response()->json([
            'status' => true,
            'serviceProvider' => $serviceProvider,
        ]);
    }
}
