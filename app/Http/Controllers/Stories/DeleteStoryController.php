<?php

namespace App\Http\Controllers\Stories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoryFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class DeleteStoryController extends Controller
{
    public function destroy($id)
    {
        $storyFile = StoryFile::find($id);

        if (!$storyFile) {
            return response()->json(['error' => 'Story file not found'], 404);
        }

        Storage::delete('public/stories/'.$storyFile->file);
        $storyFile->delete();

        return response()->json([
            'status' => true,
            'message' => 'Story file deleted successfully'], 200);
    }
}
