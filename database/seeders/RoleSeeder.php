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
            Permission::create(['name' => $permission]);
        }

        // Create SuperAdmin role
        $superAdmin = Role::create(['name' => 'SuperAdmin']);
        $superAdmin->givePermissionTo([
            'invite_admin',
            'invite_member',
            'view_all_short_urls',
            'manage_company',
            'manage_users',
        ]);

        // Create Admin role
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'invite_member',
            'create_short_url',
            'view_company_short_urls',
            'manage_users',
        ]);

        $member = Role::create(['name' => 'Member']);
        $member->givePermissionTo([
            'create_short_url',
            'view_own_short_urls',
        ]);

        // Create Sales role
        $sales = Role::create(['name' => 'Sales']);

        // Create Manager role
        $manager = Role::create(['name' => 'Manager']);

        \DB::statement("
            INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
            VALUES ('Super Admin', 'superadmin@example.com', '" . bcrypt('Test@123') . "', NOW(), NOW(), NOW())
        ");

        $superAdminUser = User::where('email', 'superadmin@example.com')->first();
        $superAdminUser->assignRole('SuperAdmin');

        // Create a default company
        $company = Company::create([
            'name' => 'Test Company',
            'domain' => 'test.com',
        ]);

        // Create Admin user
        $adminUser = User::create([
            'name' => 'Company Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('Test@123'),
            'company_id' => $company->id,
        ]);
        $adminUser->assignRole('Admin');

        // Create Member user
        $memberUser = User::create([
            'name' => 'Company Member',
            'email' => 'member@example.com',
            'password' => bcrypt('Test@123'),
            'company_id' => $company->id,
        ]);
        $memberUser->assignRole('Member');
    }
}
