<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $password = '123123123';
        User::factory()->create([
            'name' => 'Super User',
            'email' => 'fakeemail@gmail.com',
            'password' => '123123123', // Passwort für den Testnutzer
            'role' => 'leitung', // Rolle für den Testnutzer
        ]);
    }
}

//seed in terminal:  ./vendor/bin/sail artisan db:seed


// Template for adding users manually:   
/* Use this inside: ./vendor/bin/sail artisan tinker:

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Max Mustermann',
    'username' => 'max123',
    'email' => 'max@example.com',
    'password' => '123123123',
    'role' => 'mitarbeiter', // options: 'leitung', 'techniker', 'mitarbeiter'
]);
*/

//  Change password for an existing user:
// Use this inside: ./vendor/bin/sail artisan tinker

// $user = User::where('email', 'ACTUAL_EMAIL')->first();
// $user->password = Hash::make('ACTUAL_NEW_PASSWORD');
// $user->save();
