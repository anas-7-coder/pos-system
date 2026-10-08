<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
            ],
            [
                'name' => 'cashier',
                'display_name' => 'Cashier',
            ],
            [
                'name' => 'inventory_manager',
                'display_name' => 'Inventory Manager',
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
