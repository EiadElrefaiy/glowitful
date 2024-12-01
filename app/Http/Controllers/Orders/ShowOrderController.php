<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowOrderController extends Controller
{
    public function showSingleOrder($id)
    {
        $order = Order::with("orderServices")->find($id);
        $username = User::find($order->user_id)->name;
        $order->username = $username;

        $order_provider_id = ServiceProvider::find($order->serviceprovider_id)->user_id;
        $order_provider_name = User::find($order_provider_id)->name;
        $order->provider_name = $order_provider_name;

        return response()->json([
            'status' => true,
            'order' => $order,
        ]);
    }
}
