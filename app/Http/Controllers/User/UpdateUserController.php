<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\ValidationTrait;
use App\Traits\PasswordValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UpdateUserController extends Controller
{
    public function update(Request $request)
    {    
        try {
        $token = $request->bearerToken();
        $decoded = JWTAuth::setToken($token)->getPayload();
        $user_id = $decoded['sub'];
        $user = User::find($user_id);

        $validator =  $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','email','max:255',Rule::unique('users')->ignore($user->id),],
            'phone' => ['required','string','max:255',Rule::unique('users')->ignore($user->id),],
            'city' => ['required', 'string', 'max:255'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'image' => ['sometimes', 'nullable', 'mimes:jpeg,png,jpg,gif,svg', 'image', 'max:2048'],
    ]);
        
    } catch (ValidationException $e) {
        // Return JSON response for validation errors
        return response()->json([
            'status' => false,
            'errors' => $e->errors()], 422);
    }        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        if($request->delete_image == 1){
            $user->update([
                'image' => null
        ]);
        
        }else{
            if ($request->hasFile('image')) {
                Storage::delete('public/images/users/'.$user->image);
                $fileName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/images/users', $fileName);
                $user->update([
                    'image' => $fileName,
                ]);
            }    
        }    


    
        return response()->json([
            'status' => true,
            'message' => 'User updated successfully'
        ], 201);
    }

        public function updateLocation(Request $request)
        {    
            try {
            $token = $request->bearerToken();
            $decoded = JWTAuth::setToken($token)->getPayload();
            $user_id = $decoded['sub'];
            $user = User::find($user_id);

            $user->update([
                'location_lat' => $request->location_lat,
                'location_long' => $request->location_long,
            ]);
        }
         catch (ValidationException $e) {
            // Return JSON response for validation errors
            return response()->json([
                'status' => false,
                'errors' => $e->errors()], 422);
        }        
    
            return response()->json([
                'status' => true,
                'message' => 'User Location updated successfully'
            ], 201);

        }
}



