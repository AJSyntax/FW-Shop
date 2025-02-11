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
            // Generate random salt
            $salt = bin2hex(random_bytes(16)); // Using bin2hex for consistent 32-character length
            
            // Store the plain password (for educational purposes only!)
            $plainPassword = $request->password;
            
            // Create regular hash without salt
            $hashedPassword = Hash::make($plainPassword);
            
            // Create salted hash by combining password and salt
            $saltedHashedPassword = Hash::make($plainPassword . $salt);

            // Create user with all password variations
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->salt = $salt;
            $user->plain_password = $plainPassword;           // Store plain text (educational only!)
            $user->hashed_password = $hashedPassword;        // Store regular hash
            $user->salted_hashed_password = $saltedHashedPassword; // Store salted hash
            $user->password = $saltedHashedPassword;         // Use salted hash for auth
            $user->save();

            event(new Registered($user));
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            \Log::error('User registration error: ' . $e->getMessage());
            throw $e;
        }
    }
} 