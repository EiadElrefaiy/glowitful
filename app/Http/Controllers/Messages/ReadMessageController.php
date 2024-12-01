<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class ReadMessageController extends Controller
{
    public function show($id)
    {
        $message = Message::find($id);

        return response()->json(['status' => true, 'message' => $message]);
    }
}
