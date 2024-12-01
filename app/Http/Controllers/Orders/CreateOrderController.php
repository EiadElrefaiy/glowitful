<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;
use App\Models\OrderServices;

class CreateOrderController extends Controller
{
            public function store(Request $request)
            {
                try {
                    // Validate the incoming request data for order creation
                    $validatedOrderData = $request->validate([
                        'serviceprovider_id' => ['required', 'exists:service_providers,id'],
                        'total' => ['required', 'numeric'],
                        'date_time' => ['required', 'string'],
                        'type' => ['required', 'numeric'],
                        'duration_hours' => ['required', 'integer', 'min:0'],
                        'duration_minutes' => ['required', 'integer', 'min:0', 'max:59'],
                        'order_services.*.name' => ['required', 'string'],
                        'order_services.*.price' => ['required', 'numeric'],
                        'order_services.*.type' => ['required', 'numeric'],
                    ]);
            
                    // Retrieve user_id from the bearer token
                    $token = $request->bearerToken();
                    $decoded = JWTAuth::setToken($token)->getPayload();
                    $user_id = $decoded['sub'];
            
                    // Create a new order
                    $order = Order::create([
                        "user_id" => $user_id,
                        "serviceprovider_id" => $validatedOrderData['serviceprovider_id'],
                        "total" => $validatedOrderData['total'],
                        "date_time" => $validatedOrderData['date_time'],
                        "status" => 0,
                        "type" => $validatedOrderData['type'],
                        'duration_hours' => $validatedOrderData['duration_hours'],
                        'duration_minutes' => $validatedOrderData['duration_minutes'],
                    ]);
            
                    // Create order services and associate them with the order
                    $createdOrderServices = [];
                    foreach ($validatedOrderData['order_services'] as $orderServiceData) {
                        $order_service = OrderServices::create([
                            'order_id' => $order->id,
                            'name' => $orderServiceData['name'],
                            'price' => $orderServiceData['price'],
                            'type' => $orderServiceData['type'],
                        ]);
                        $createdOrderServices[] = $order_service;
                    }
            
                    // Return JSON response indicating success
                    return response()->json([
                        'status' => true,
                        'message' => 'Order and services added successfully',
                        'order' => $order,
                        'order_services' => $createdOrderServices,
                    ]);
                } catch (ValidationException $e) {
                    // Return JSON response for validation errors
                    return response()->json(['errors' => $e->errors()], 422);
                }
            }
        }