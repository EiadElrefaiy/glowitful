<?php

namespace App\Http\Controllers\ServiceProviders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Models\Service;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilterController extends Controller
{
    public function filterPrice(Request $request)
    {
        $type_providers_id = ServiceProvider::where('type' , $request->type)->pluck("user_id");
        $users = User::whereIn("id" , $type_providers_id)->get();
        for($n = 0; $n < count($users); $n++){
        $service_provider = ServiceProvider::where("user_id" , $users[$n]->id)->get();  
        $service_sum = Service::where("serviceprovider_id" , $service_provider[0]->id)->sum('price');
        $users[$n]->price_sum = $service_sum; 

        $service_provider_review = ServiceProvider::where("user_id" , $users[$n]->id)->pluck("review")[0];
        $users[$n]->review = $service_provider_review;
        }

        if($request->filterType == "desc"){
            $users = $users->sortByDesc('price_sum');
        }else{
            $users = $users->sortBy('price_sum');
        }
        $users_filter_price = $users->values()->toArray();
        return response()->json([
            'users' => $users_filter_price, 
        ]);
    }

    public function filterReview(Request $request)
    {
        $typeProvidersIds = explode(',', $request->typeProvidersIds);
        $type_providers_id = ServiceProvider::where('type' , $request->type)->whereIn("id" , $typeProvidersIds)->pluck("user_id");
        $users = User::whereIn("id" , $type_providers_id)->get();
        
        for($n = 0; $n < count($users); $n++){
            $service_provider = ServiceProvider::where("user_id" , $users[$n]->id)->get();  
            $service_sum = Service::where("serviceprovider_id" , $service_provider[0]->id)->sum('price');
            $users[$n]->price_sum = $service_sum; 
    
            $service_provider_review = ServiceProvider::where("user_id" , $users[$n]->id)->pluck("review")[0];
            $users[$n]->review = $service_provider_review;
            }    
            if($request->filterType == "desc"){
                $users = $users->sortByDesc('review');
            }else{
                $users = $users->sortBy('review');
            }
            $users_filter_review = $users->values()->toArray();
            return response()->json([
            'status' => true,
            'users' =>  $users_filter_review, 
        ]);
    }

    public function filterLocation(Request $request)
        {    
            $lat = $request->location_lat;
            
            $long = $request->location_long;
            
            $typeProvidersIds = explode(',', $request->typeProvidersIds);

            $type_providers_id = ServiceProvider::where('type' , $request->type)->whereIn("id" , $typeProvidersIds)->pluck("user_id");

            $users = User::whereIn("id" , $type_providers_id)->get();

            for($n = 0; $n < count($users); $n++){
                $service_provider = ServiceProvider::where("user_id" , $users[$n]->id)->get();  
                $service_sum = Service::where("serviceprovider_id" , $service_provider[0]->id)->sum('price');
                $users[$n]->price_sum = $service_sum; 
        
                $service_provider_review = ServiceProvider::where("user_id" , $users[$n]->id)->pluck("review")[0];
                $users[$n]->review = $service_provider_review;

                $users[$n]->distance = $this->haversineDistance($lat, $long, $users[$n]->location_lat, $users[$n]->location_long);
            }

            
            if($request->filterType == "desc"){
                $users = $users->sortBy('distance')->sortByDesc('review');
            }else{
                $users = $users->sortBy('distance')->sortBy('review');
            }

            return response()->json([
                'status' => true,
                'users' => $users->values()->toArray(),
            ]);
        }

        private function haversineDistance($lat1, $lon1, $lat2, $lon2) {
            $earthRadius = 6371; 

            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = $earthRadius * $c;

            return $distance;
        }

}
