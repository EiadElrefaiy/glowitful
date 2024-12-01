<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Message;
use App\Models\User;

class ShowUserMessagesController extends Controller
{
    public function showRecived(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $message = Message::where("receiver_id" , $user_id)->get();

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function showSend(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $message = Message::where("sender_id" , $user_id)->get();

        for($n = 0; $n < count($message); $n++){
            $sender = User::find($message[$n]->sender_id);
            $message[$n]->sender_image = $sender->image;
        }

        return response()->json(['status' => true, 'message' => $message]);
    }
}
