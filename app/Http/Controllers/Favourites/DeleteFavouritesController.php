<?php

namespace App\Http\Controllers\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;

class DeleteFavouritesController extends Controller
{
    public function destroy($id)
    {
        $favourite = Favourite::findOrFail($id);
        $favourite->delete();

        return response()->json(['status' => true, 'message' => 'Favourite deleted successfully'], 200);
    }
}
