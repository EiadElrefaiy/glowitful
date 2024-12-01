<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class MapController extends Controller
{
    public function show(Request $request , $id)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $user = User::findOrFail($user_id);

        $user->update([
            'location_lat' => $request->location_lat,
            'location_long' => $request->location_long,
        ]);
        
        $order = Order::findOrFail($id);

        $order_user = User::find($order->user_id);
        $user_lat = $order_user->location_lat;
        $user_long= $order_user->location_long;

        $order_provider_id = ServiceProvider::find($order->serviceprovider_id)->user_id;
        $order_provider = User::find($order_provider_id);
        $provider_lat = $order_provider->location_lat;
        $provider_long= $order_provider->location_long;

        $order->user_lat = $user_lat;
        $order->user_long = $user_long;

        $order->provider_lat = $provider_lat;
        $order->provider_long = $provider_long;

        $order->user = $order_user;
        $order->provider = $order_provider;
        return response()->json([
            'status' => true,
            'order' => $order,
        ]);
    }
}
