<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin',           'email' => 'admin@test.local',           'role' => 'admin'],
            ['name' => 'Purchasing Staff', 'email' => 'purchasing-staff@test.local', 'role' => 'purchasing-staff'],
            ['name' => 'Sales Staff',      'email' => 'sales-staff@test.local',      'role' => 'sales-staff'],
            ['name' => 'Finance',          'email' => 'finance@test.local',          'role' => 'finance'],
            ['name' => 'Manager',          'email' => 'manager@test.local',          'role' => 'manager'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole($data['role']);
        }

        $this->command->info('5 dummy users berhasil dibuat!');
    }
}
