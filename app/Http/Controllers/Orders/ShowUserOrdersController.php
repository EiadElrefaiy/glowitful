<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ServiceProvider;

class ShowUserOrdersController extends Controller
{
    public function show(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $orders = Order::with("orderServices")->where("user_id" , $user_id)->where("status" , $request->status)->get();

        for($n = 0; $n < count($orders); $n++){
            $order_provider_id = ServiceProvider::find($orders[$n]->serviceprovider_id)->user_id;
            $order_provider_name = User::find($order_provider_id)->name;
            $orders[$n]->order_provider_name = $order_provider_name;
        }
    
        return response()->json([
            'status' => true,
            'orders' => $orders,
        ]);
    }
}
