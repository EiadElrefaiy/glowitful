<?php

namespace App\Http\Controllers\ServiceProviders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Models\User;

class SearchAndFiltersController extends Controller
{
    public function search(Request $request)
    {
        $type_providers_id = ServiceProvider::where('type' , $request->type)->pluck("user_id");
        $users = User::whereIn("id" , $type_providers_id)->where("name", "like", '%' . $request->value . '%')->get();
        for($n = 0 ; $n < count($users); $n++){
            $service_provider_review = ServiceProvider::where("user_id" , $users[$n]->id)->pluck("review")[0];
            $service_provider_id = ServiceProvider::where("user_id" , $users[$n]->id)->pluck("id")[0];
            $users[$n]->review = $service_provider_review;
            $users[$n]->service_provider_id = $service_provider_id;
        }
        return response()->json([
            'status' => true,
            'users' => $users , 
        ]);
    }
}
