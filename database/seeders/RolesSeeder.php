<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationAdminRole = Role::create(['name' => 'organization_admin']);

        $organizationPermissions = collect([
            ['name' => 'create user'],
            ['name' => 'edit arcticles'],
        ])->each(fn ($permission) => Permission::create($permission));

        $organizationAdminRole->givePermissionTo($organizationPermissions->toArray());
    }
}
