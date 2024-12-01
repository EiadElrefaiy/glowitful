<?php

// app/Traits/PasswordValidationTrait.php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait PasswordValidationTrait
{
    protected function validatePassword(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
