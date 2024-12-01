<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DeleteOrderController extends Controller
{
    public function destroy($id)
    {
        $order = Order::find($id);

        $order->delete();

        return response()->json(['status' => true ,'message' => 'Order deleted successfully'], 200);
    }
}
