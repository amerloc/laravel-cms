<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $editorRole->givePermissionTo([
            'view users',
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
        ]);
        
        $userRole->givePermissionTo([
            'view posts',
            'create posts',
            'edit posts',
        ]);
    }
}