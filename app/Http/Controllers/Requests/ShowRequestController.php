<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestService;
use App\Models\ServiceProvider;
use App\Models\User;

class ShowRequestController extends Controller
{
    public function show($id)
    {
        // Find the request service by ID
        $requestService = RequestService::findOrFail($id);

            $service_provider = ServiceProvider::find($requestService->serviceprovider_id);
            $user = User::find($service_provider->user_id);
            $requestService->provider_image = $user->image;
        
            $user_provider_name = User::where("id" , $service_provider->user_id)->pluck("name")[0];
            $requestService->provider_name= $user_provider_name;

        // Return the view with the request service data
        return response()->json([
            'status' => true,
            'requestService' => $requestService,
        ]);
    }
}
