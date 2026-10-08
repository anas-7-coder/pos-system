<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'email' => 'admin@pos.test',
            ],
            [
                'name' => 'POS Administrator',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $adminRole = Role::where('name', 'super_admin')->firstOrFail();

        $user->roles()->syncWithoutDetaching([
            $adminRole->id,
        ]);
    }
}
