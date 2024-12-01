<?php

namespace App\Http\Controllers\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateFavouriteController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $user = User::find($user_id); 

        $request->validate([
            'story_id' => ['required', 'exists:story_files,id'],
        ]);

        $favourite = Favourite::create([
            'user_id' => $user_id,
            'story_id' => $request->story_id,
        ]);

        return response()->json(['status' => true,'favourite' => $favourite], 201);
    }
}
