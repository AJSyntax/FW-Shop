<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        try {
            // 1️⃣ Generate random salt (32 characters)
            $salt = bin2hex(random_bytes(16));
            if (empty($salt)) {
                throw new \Exception('Failed to generate salt');
            }

            // 2️⃣ Get plain password
            $plainPassword = $request->password;

            // 3️⃣ Generate SHA1 hash
            $sha1Hash = sha1($plainPassword);

            // 4️⃣ Generate salted SHA1 hash
            $saltedSha1Hash = sha1($plainPassword . $salt);

            // 5️⃣ Create user with direct attribute assignment
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($plainPassword);
            $user->salt = $salt;
            $user->plain_password = $plainPassword;
            $user->hashed_password = $sha1Hash;
            $user->salted_hashed_password = $saltedSha1Hash;
            
            if (!$user->save()) {
                throw new \Exception('Failed to save user');
            }

            \Log::info('User registration successful', [
                'user_id' => $user->id,
                'has_salt' => !empty($user->salt),
                'has_plain' => !empty($user->plain_password),
                'has_hashed' => !empty($user->hashed_password),
                'has_salted' => !empty($user->salted_hashed_password)
            ]);

            event(new Registered($user));
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            \Log::error('User registration error: ' . $e->getMessage());
            throw $e;
        }
    }
} 