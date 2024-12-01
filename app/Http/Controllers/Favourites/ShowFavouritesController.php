<?php

namespace App\Http\Controllers\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShowFavouritesController extends Controller
{
    public function show(Request $request)
    {
        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $user = User::find($user_id); 

        $favourites = Favourite::where("user_id" , $user_id)->get();

        return response()->json(['status' => true, 'favourites' => $favourites], 200);
    }
}
