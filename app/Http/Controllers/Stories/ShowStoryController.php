<?php

namespace App\Http\Controllers\Stories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoryFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ServiceProvider;

class ShowStoryController extends Controller
{
    public function show($provider_id)
    {
        $storyFile = StoryFile::where("serviceprovider_id", $provider_id)->whereRaw('story_files.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)')->get();
        $user_id = ServiceProvider::where("id", $provider_id)->pluck("user_id")[0];
        $username = User::find($user_id)->name;
        $userimage = User::find($user_id)->image;


        if (!$storyFile) {
            return response()->json(['error' => 'Story file not found'], 404);
        }

        return response()->json([
            'status' => true,
            'username' => $username,
            'userimage' => $userimage,
            'data' => $storyFile], 200);
    }
}
