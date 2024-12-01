<?php

// app/Traits/ValidationTrait.php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidationTrait
{
    protected function validateSignUpRequest(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'city' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
