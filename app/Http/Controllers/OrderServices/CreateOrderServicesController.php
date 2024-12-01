<?php

namespace App\Http\Controllers\OrderServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderServices;
use Illuminate\Validation\ValidationException;

class CreateOrderServicesController extends Controller
{
    public function store(Request $request, $order_id)
    {
        try {
        // Validate the incoming request data
        $request->validate([
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'type' => ['required', 'numeric'],
        ]);

        // Find the order by order_id
        $order = Order::findOrFail($order_id);

        // Create a new order service
        $order_service = OrderServices::create([
            'order_id' => $order_id,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'type' => $request->input('type'),
        ]);

        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'Service added to Order added successfully', 
            'order_service' => $order_service,
        ]);
      }
        catch (ValidationException $e) {
        // Return JSON response for validation errors
        return response()->json(['errors' => $e->errors()], 422);
     }    
   }
}
