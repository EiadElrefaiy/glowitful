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

class ShowAllProvidersControllers extends Controller
{
    public function show()
    {
        $serviceProviders = ServiceProvider::with("Services")->get();

        for($n =0; $n < count($serviceProviders); $n++){
           $user_name = User::where("id" , $serviceProviders[$n]->user_id)->pluck("name")[0];
           $user_image = User::where("id" , $serviceProviders[$n]->user_id)->pluck("image")[0];
           $serviceProviders[$n]->name = $user_name;
           $serviceProviders[$n]->image = $user_image;
        }

        return response()->json([
            'serviceProvider' => $serviceProviders,
        ]);
    }

    public function showType(Request $request)
    {
        $serviceProviders = ServiceProvider::with("Services")->where("type" , $request->type)->get();

        for($n =0; $n < count($serviceProviders); $n++){
            $user_name = User::where("id" , $serviceProviders[$n]->user_id)->pluck("name")[0];
            $user_image = User::where("id" , $serviceProviders[$n]->user_id)->pluck("image")[0];
            $serviceProviders[$n]->name = $user_name;
            $serviceProviders[$n]->image = $user_image;
         }
 
        return response()->json([
            'status' => true,
            'serviceProvider' => $serviceProviders,
        ]);
    }
}
