<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceProvider;
use App\Models\Order;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowProviderOrdersController extends Controller
{
    public function show(Request $request)
 {
    $token = $request->bearerToken();

    $decoded = JWTAuth::setToken($token)->getPayload();

    $user_id = $decoded['sub'];

    $provider_id = ServiceProvider::where("user_id" , $user_id)->pluck('id')[0];

    $orders = Order::with("orderServices")->where("serviceprovider_id" , $provider_id)->where("status" , $request->status)->get();

    for($n = 0; $n < count($orders); $n++){
        $username = User::find($orders[$n]->user_id)->name;
        $orders[$n]->username = $username;
    }

    return response()->json([
        'status' => true,
        'orders' => $orders,
    ]);
 }
}
