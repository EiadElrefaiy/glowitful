<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateMessageController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $message = Message::create([
            'sender_id' => $user_id,
            'receiver_id' => $request->receiver_id,
            'content' => $request->content
        ]);

        return response()->json(['status' => true, 'message' => $message], 201);
    }
}
