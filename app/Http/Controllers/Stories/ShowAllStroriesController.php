<?php

namespace App\Http\Controllers\Stories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceProvider;
use App\Models\StoryFile;
use Illuminate\Support\Facades\DB;

class ShowAllStroriesController extends Controller
{
    public function showAll(Request $request) {
        
        $token = $request->bearerToken();
    
        
        $decoded = JWTAuth::setToken($token)->getPayload();
        $user_id = $decoded['sub'];
    
        
        $provider_id = ServiceProvider::where("user_id" , $user_id)->pluck("id")->first();
    
       
        $provider_storyfiles = StoryFile::select('story_files.*')
            ->where('serviceprovider_id', $provider_id)
            ->where('ended', 0)
            ->whereRaw('created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)')
            ->orderBy('id', 'asc')
            ->get();
    
        
        $other_storyfiles = StoryFile::select('story_files.*')
            ->where('serviceprovider_id', '!=', $provider_id)
            ->where('ended', 0)
            ->whereRaw('created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)')
            ->orderBy('id', 'asc')
            ->get();
    
        
        $storyfiles = $provider_storyfiles->merge($other_storyfiles);
    
        
        foreach($storyfiles as $storyfile) {
            $provider = ServiceProvider::find($storyfile->serviceprovider_id);
            $user = User::find($provider->user_id);
    
            $storyfile->provider_name = $user->name;
            $storyfile->provider_image = $user->image;
        }
    
        
        return response()->json([
            'status' => true,
            'storyfiles' => $storyfiles
        ], 200);
    }
    }

