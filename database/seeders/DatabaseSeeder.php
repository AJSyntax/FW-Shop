<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Import Str facade
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update the Admin user
        $adminEmail = 'admin@example.com';
        $plainPassword = 'password'; // Use a secure password in production!
        $salt = Str::random(16); // Generate a salt

        User::updateOrCreate(
            ['email' => $adminEmail], // Find user by email
            [
                'name' => 'Admin User',
                'password' => Hash::make($plainPassword), // Hash the password
                'email_verified_at' => now(),
                'role' => 'admin', // Set the role to admin
                'salt' => $salt, // Provide the salt
                'plain_password' => $plainPassword, // Store plain password (if needed by app logic)
                'hashed_password' => sha1($plainPassword), // Store sha1 hash (if needed by app logic)
                'salted_hashed_password' => sha1($plainPassword . $salt), // Store salted hash (if needed by app logic)
                'remember_token' => Str::random(10), // Add remember token
            ]
        );

        // Optionally create some buyer users using the factory if needed
        // User::factory(5)->create(); // Example: Create 5 buyer users

        $this->call([
            CategorySeeder::class, // Ensure categories are seeded first
            DesignSeeder::class,   // Add the DesignSeeder
            SettingsSeeder::class,
            SecurityQuestionSeeder::class, // Add security questions
            // ... other seeders if any
        ]);
    }
}
