<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\ShortUrl;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'SuperAdmin']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Member']);
    }

    public function test_superadmin_cannot_create_short_urls()
    {
        $user = User::factory()->create();
        $user->assignRole('SuperAdmin');

        $this->actingAs($user);

        $response = $this->post('/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'SuperAdmin cannot create short URLs.');
    }

    public function test_admin_can_create_short_urls()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);
        $user->assignRole('Admin');

        $this->actingAs($user);

        $response = $this->post('/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertRedirect(route('short-urls.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://example.com',
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_member_can_create_short_urls()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);
        $user->assignRole('Member');

        $this->actingAs($user);

        $response = $this->post('/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertRedirect(route('short-urls.index'));
        $response->assertSessionHas('success');
    }

    public function test_admin_can_only_see_own_company_short_urls()
    {
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();

        $admin = User::factory()->create(['company_id' => $company1->id]);
        $admin->assignRole('Admin');

        $otherUser = User::factory()->create(['company_id' => $company2->id]);
        $otherUser->assignRole('Member');

        ShortUrl::factory()->create(['user_id' => $admin->id, 'company_id' => $company1->id]);
        ShortUrl::factory()->create(['user_id' => $otherUser->id, 'company_id' => $company2->id]);

        $this->actingAs($admin);

        $response = $this->get('/short-urls');

        $response->assertOk();
        $response->assertViewHas('shortUrls', function ($shortUrls) {
            return $shortUrls->count() === 1;
        });
    }

    public function test_member_can_only_see_own_short_urls()
    {
        $company = Company::factory()->create();

        $member1 = User::factory()->create(['company_id' => $company->id]);
        $member1->assignRole('Member');

        $member2 = User::factory()->create(['company_id' => $company->id]);
        $member2->assignRole('Member');

        ShortUrl::factory()->create(['user_id' => $member1->id, 'company_id' => $company->id]);
        ShortUrl::factory()->create(['user_id' => $member2->id, 'company_id' => $company->id]);

        $this->actingAs($member1);

        $response = $this->get('/short-urls');

        $response->assertOk();
        $response->assertViewHas('shortUrls', function ($shortUrls) {
            return $shortUrls->count() === 1;
        });
    }

    public function test_short_url_redirects_to_original_url()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);
        $user->assignRole('Member');

        $shortUrl = ShortUrl::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'original_url' => 'https://example.com',
            'short_code' => 'abc123',
            'is_active' => true,
        ]);

        $response = $this->get('/r/abc123');

        $response->assertRedirect('https://example.com');

        $this->assertDatabaseHas('short_urls', [
            'id' => $shortUrl->id,
            'clicks' => 1,
        ]);
    }
}
