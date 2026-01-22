<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Company;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'invite_admin',
            'invite_member',
            'create_short_url',
            'view_all_short_urls',
            'view_company_short_urls',
            'view_own_short_urls',
            'manage_company',
            'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $superAdmin->syncPermissions([
            'invite_admin',
            'invite_member',
            'view_all_short_urls',
            'manage_company',
            'manage_users',
        ]);

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'invite_member',
            'create_short_url',
            'view_company_short_urls',
            'manage_users',
        ]);

        $member = Role::firstOrCreate(['name' => 'Member']);
        $member->syncPermissions([
            'create_short_url',
            'view_own_short_urls',
        ]);

        $sales = Role::firstOrCreate(['name' => 'Sales']);
        $manager = Role::firstOrCreate(['name' => 'Manager']);

        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Test@123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole('SuperAdmin');

        $company = Company::firstOrCreate([
            'domain' => 'test.com'
        ], [
            'name' => 'Test Company',
        ]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Company Admin',
                'password' => bcrypt('Test@123'),
                'company_id' => $company->id,
            ]
        );
        $adminUser->assignRole('Admin');

        $memberUser = User::firstOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'Company Member',
                'password' => bcrypt('Test@123'),
                'company_id' => $company->id,
            ]
        );
        $memberUser->assignRole('Member');
    }
}
