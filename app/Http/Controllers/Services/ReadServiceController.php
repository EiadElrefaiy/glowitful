<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\ServiceProvider;
use App\Models\Service;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ReadServiceController extends Controller
{
    public function show($id)
    {

        $service = Service::findOrFail($id);

        return response()->json([
            'status' => true,
            'service' => $service,
        ]);
    }
}
