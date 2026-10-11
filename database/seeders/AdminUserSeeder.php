<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed default Super Admin and Sales team accounts.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@beta-industrial.com'],
            [
                'public_id' => (string) Str::ulid(),
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@Beta2026!'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'sales@beta-industrial.com'],
            [
                'public_id' => (string) Str::ulid(),
                'name' => 'Tarek Mansour (Sales Director)',
                'password' => Hash::make('Sales@Beta2026!'),
                'role' => 'sales',
                'is_active' => true,
            ]
        );
    }
}
