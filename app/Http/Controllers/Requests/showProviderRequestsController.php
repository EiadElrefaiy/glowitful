<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Models\RequestService;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class showProviderRequestsController extends Controller
{
    public function show(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $provider_id = ServiceProvider::where("user_id" , $user_id)->pluck("id")[0];

        $user_requests = RequestService::where("serviceprovider_id" , $provider_id)->get();

        for($n = 0; $n < count($user_requests); $n++){
        $service_provider = ServiceProvider::find($user_requests[$n]->serviceprovider_id);
        $user = User::find($service_provider->user_id);
        $user_requests[$n]->provider_image = $user->image;

        $user_provider_name = User::where("id" , $service_provider->user_id)->pluck("name")[0];
        $user_requests[$n]->provider_name= $user_provider_name;
        }

        return response()->json([
            'status' => true,
            'user_requests' => $user_requests,
        ]);
    }
}
