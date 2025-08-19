<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Promokna',
            'email' => 'admin@promokna.id',
            'phone' => '088232400859',
            'password' => Hash::make('admin123'),
            'role' => 'super-admin',
        ]);
    }
}
