<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdmin2Seeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin2@example.com'
        ], [
            'name' => 'Administrator 2',
            'password' => Hash::make('Admin1234!'),
            'email_verified_at' => now(),
            'role' => 'admin'
        ]);
    }
}
