<?php

namespace App\Http\Controllers\Stories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoryFile;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ServiceProvider;

class CreateStoryController extends Controller
{
    public function store(Request $request)
    {

        $token = $request->bearerToken();

        $decoded = JWTAuth::setToken($token)->getPayload();

        $user_id = $decoded['sub'];

        $user = User::find($user_id); 
        
        $service_provider_id = ServiceProvider::where("user_id" , $user_id)->pluck("id")[0];

        try {
            $validatedData = $request->validate([
                'file' => [
                    'required',
                    'file',
                    'mimes:jpeg,png,gif,mp4,mov,avi,wmv',
                    'max:20480',
                ],
                'ended' => ['integer'],
            ]);

                $fileName = time() . '.' . $request->file('file')->extension();
                $request->file('file')->storeAs('public/stories', $fileName);

                $story_file = StoryFile::create([
                    'serviceprovider_id' => $service_provider_id,
                    'file' => $fileName ?? null,
                    'ended' => $validatedData['ended']
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Story added successfully',
                    'story_file' => $story_file,
                ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()], 500);
        }
    }
}