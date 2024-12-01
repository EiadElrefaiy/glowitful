<?php

namespace App\Http\Controllers\OrderServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderServices;

class DeleteOrderServiceController extends Controller
{
    public function destroy($id)
    {
        // Find the order service by ID
        $orderService = OrderServices::findOrFail($id);

        // Delete the order service
        $orderService->delete();

        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'Service deleted from Order added successfully', 
        ]);
    }
}
