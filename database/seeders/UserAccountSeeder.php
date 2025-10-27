<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;

class UserAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserAccount::create([
            'name' => 'Test User Account',
            'email' => 'test@student.unsika.ac.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        UserAccount::create([
            'name' => 'Unverified User',
            'email' => 'unverified@student.unsika.ac.id',
            'password' => Hash::make('password'),
            'email_verified_at' => null,
        ]);
    }
}
