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

class DeleteServiceController extends Controller
{
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // Delete associated image if it exists
        if ($service->image) {
            Storage::delete('public/images/services/' . $service->image);
        }

        $service->delete();

        return response()->json([
            'status' => true,
            'message' => 'Service deleted successfully' , 
        ]);
    }

}
