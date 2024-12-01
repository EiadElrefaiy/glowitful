<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class DeleteMessageController extends Controller
{
    public function delete($id)
    {
        $message = Message::findOrFail($id);

        $message->delete();

        // Redirect back or to a specific route
        return response()->json([
            'status' => true,
            'message' => 'message deleted successfully' , 
        ]);
    }
}
