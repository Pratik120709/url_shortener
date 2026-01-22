<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'SuperAdmin']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Member']);
    }

    public function test_superadmin_can_invite_admin()
    {
        Mail::fake();

        $superadmin = User::factory()->create();
        $superadmin->assignRole('SuperAdmin');

        $company = Company::factory()->create();

        $this->actingAs($superadmin);

        $response = $this->post('/invitations', [
            'email' => 'newadmin@example.com',
            'role' => 'Admin',
            'company_id' => $company->id,
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@example.com',
            'company_id' => $company->id,
        ]);
    }

    public function test_admin_can_only_invite_member_to_own_company()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id]);
        $admin->assignRole('Admin');

        $this->actingAs($admin);

        $response = $this->post('/invitations', [
            'email' => 'newmember@example.com',
            'role' => 'Member',
            'company_id' => $company->id,
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');
    }

    public function test_admin_cannot_invite_admin()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['company_id' => $company->id]);
        $admin->assignRole('Admin');

        $this->actingAs($admin);

        $response = $this->post('/invitations', [
            'email' => 'newadmin@example.com',
            'role' => 'Admin',
            'company_id' => $company->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
