<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'password.min' => 'The password must be at least 12 characters.',
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ])->validate();

        $salt = bin2hex(random_bytes(16));
        $plainPassword = $input['password'];

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($plainPassword),
            'salt' => $salt,
            'plain_password' => '********', // No longer storing actual plain password
            'hashed_password' => sha1($plainPassword),
            'salted_hashed_password' => sha1($plainPassword . $salt),
            'role' => 'buyer', // Set default role
        ]);
    }
}
