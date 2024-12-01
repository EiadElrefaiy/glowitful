<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\ServiceProvider;
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



class AuthController extends Controller
{
    use ValidationTrait , PasswordValidationTrait;

    public function register(Request $request)
    {
        $validator = $this->validateSignUpRequest($request->all());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $this->createUser($request->all());

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token, 
            'user' => $user, 
            'message' => 'User registered successfully'], 201
        );
    }

    protected function createUser(array $data)
    {
       $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'gender' => $data['gender'],
            'goal' => $data['goal'],
            'location_lat' => 0.000000,
            'location_long' => 0.000000,
            'phone_verified' => 0,
            'gmailToken' => $data['gmailToken'] ?? "",
            'facebookToken' => $data['facebook_token'] ?? "",
            'password' => Hash::make($data['password']),
            'remember_token' => $data['remember_token'],
        ]);
        
        if($data['goal'] == 0){
            $serviceProvider = ServiceProvider::create([
                'user_id' => $user->id,
                'location_lat' => 0.000000,
                'location_long' => 0.000000,
                'address' => null,
                'review' => 0,
                'type' => 0
            ]);    
        }
        
        return $user;
    }

        public function sendOtp(Request $request)
        {
            $token = $request->bearerToken();

            $decoded = JWTAuth::setToken($token)->getPayload();
    
            $user_id = $decoded['sub'];

            $record = User::find($user_id);

            $record->update([
                "remember_token" => $request->otp,
            ]);

            return response()->json(['status' => true, 'msg' => "otp created successfully"], 200);
        }
    

        public function phoneVerified(Request $request)
        {
            $token = $request->bearerToken();

            $decoded = JWTAuth::setToken($token)->getPayload();
    
            $user_id = $decoded['sub'];

            $record = User::find($user_id);

            if($record->remember_token != $request->otp){
                return response()->json(['status' => false, 'msg' => "Invalid Code"], 401);
            }

            $record->update([
                "phone_verified" => 1,
            ]);

            return response()->json(['status' => true, 'msg' => "phone verified successfully"], 200);
        }


        public function forgetPassword(Request $request)
        {
            $token = $request->bearerToken();

            $decoded = JWTAuth::setToken($token)->getPayload();
    
            $user_id = $decoded['sub'];

            $record = User::find($user_id);

            if($record->remember_token != $request->otp){
                return response()->json(['status' => false, 'msg' => "Invalid Code"], 401);
            }

            $record->update([
                "remember_token" => $request->otp,
            ]);

            return response()->json(['status' => true, 'msg' => "valid password reset verification"], 200);
        }

        public function resetPassword(Request $request)
        {
            $token = $request->bearerToken();

            $decoded = JWTAuth::setToken($token)->getPayload();
    
            $user_id = $decoded['sub'];

            $record = User::find($user_id);

            $validator = $this->validatePassword([$request->password]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => "Failed Password reset"], 401);
            }    

            $record->update([
                "password" =>  Hash::make($request->password),
            ]);

            return response()->json(['status' => true, 'msg' => "Password reset success"], 200);
        }
    

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        $user = Auth::user(); // Retrieve the authenticated user

        return response()->json(['token' => $token, 'user' => $user], 200);
    }


    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }



    public function loginGmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        if ($exists) {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }
    
            $user = Auth::user(); // Retrieve the authenticated user
    
            return response()->json(['token' => $token, 'user' => $user], 200);

        }    
            else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => null,
                'city' => null,
                'gender' => $request->gender,
                'goal' => $request->goal,
                'location_lat' => 0.000000,
                'location_long' => 0.000000,
                'phone_verified' => 0,
                'gmailToken' => null,
                'facebookToken' => null,
                'password' => Hash::make("gmailLogin")
            ]);

            if($request->goal == 0){
                $serviceProvider = ServiceProvider::create([
                    'user_id' => $user->id,
                    'location_lat' => 0.000000,
                    'location_long' => 0.000000,
                    'address' => null,
                    'review' => 0,
                    'type' => 0
                ]);    
            }
        
            $token = JWTAuth::fromUser($user);
    
            return response()->json([
                'token' => $token, 
                'user' => $user, 
                'message' => 'User registered successfully'], 201
            );    
            return response()->json(['success' => false, 'message' => 'Value does not exist in the table']);
        }    
    }

    public function loginFacebook(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        if ($exists) {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }
    
            $user = Auth::user(); // Retrieve the authenticated user
    
            return response()->json(['token' => $token, 'user' => $user], 200);
        }    
            else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => null,
                'city' => null,
                'gender' => $request->gender,
                'goal' => $request->goal,
                'location_lat' => 0.000000,
                'location_long' => 0.000000,
                'phone_verified' => 0,
                'gmailToken' => null,
                'facebookToken' => null,
                'password' => Hash::make("facebookLogin")
            ]);

            if($request->goal == 0){
                $serviceProvider = ServiceProvider::create([
                    'user_id' => $user->id,
                    'location_lat' => 0.000000,
                    'location_long' => 0.000000,
                    'address' => null,
                    'review' => 0,
                    'type' => 0
                ]);    
            }
    
            $token = JWTAuth::fromUser($user);
    
            return response()->json([
                'token' => $token, 
                'user' => $user, 
                'message' => 'User registered successfully'], 201
            );    
        }    
    }
}
