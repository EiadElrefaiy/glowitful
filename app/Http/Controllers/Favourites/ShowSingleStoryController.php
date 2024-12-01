<?php

namespace App\Http\Controllers\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoryFile;

class ShowSingleStoryController extends Controller
{
    public function show($id)
    {
        $story= StoryFile::find($id);;

        return response()->json(['status' => true, 'story' => $story], 200);
    }
}
