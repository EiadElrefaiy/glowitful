<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class UpdateOrderStatusController extends Controller
{
    public function update(Request $requset , $id)
    {
        $order = Order::find($id);

        $order->update([
            "status" => $requset->status,
        ]);
        return response()->json(['status' => true,'message' => 'Order updated successfully'], 200);
    }
}
