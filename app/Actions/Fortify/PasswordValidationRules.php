<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            'min:8',             // At least 8 characters
            'confirmed',
            'regex:/[a-z]/',     // At least one lowercase letter
            'regex:/[A-Z]/',     // At least one uppercase letter
            'regex:/[0-9]/',     // At least one number
            'regex:/[@$!%*#?&]/', // At least one special character
        ];
    }
}
