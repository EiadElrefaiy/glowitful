<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class UpdateMessageController extends Controller
{
    public function update(Request $request , $id)
    {
        $message = Message::findOrFail($id);

        $message->update([
            'content' => $request->input('content'),
        ]);

        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'message updated successfully' , 
        ]);
    }
}
