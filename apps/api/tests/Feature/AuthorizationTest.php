<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Testing routes registered dynamically for test isolation without polluting production routes/api.php
        Route::middleware(['auth:sanctum', 'role:OWNER'])->get('/api/v1/owner-boundary-test', function () {
            return response()->json(['message' => 'Owner access granted']);
        });

        Route::middleware(['auth:sanctum', 'role:ADMIN'])->get('/api/v1/admin-boundary-test', function () {
            return response()->json(['message' => 'Admin access granted']);
        });

        Route::middleware(['auth:sanctum', 'role:PPIC'])->get('/api/v1/ppic-boundary-test', function () {
            return response()->json(['message' => 'PPIC access granted']);
        });

        Route::middleware(['auth:sanctum', 'role:PRODUCTION_STAFF'])->get('/api/v1/staff-boundary-test', function () {
            return response()->json(['message' => 'Staff access granted']);
        });

        Route::middleware(['auth:sanctum', 'role:OWNER,PPIC'])->get('/api/v1/multi-role-boundary-test', function () {
            return response()->json(['message' => 'Multi-role access granted']);
        });
    }

    public function test_unauthenticated_request_is_rejected_with_401(): void
    {
        $response = $this->getJson('/api/v1/owner-boundary-test');

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_owner_role_access_boundaries(): void
    {
        $owner = User::factory()->create(['role' => Role::OWNER]);
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        // Allowed for OWNER
        $this->actingAs($owner, 'sanctum')
             ->getJson('/api/v1/owner-boundary-test')
             ->assertStatus(200)
             ->assertJson(['message' => 'Owner access granted']);

        // Forbidden for non-OWNER (ADMIN)
        $this->actingAs($admin, 'sanctum')
             ->getJson('/api/v1/owner-boundary-test')
             ->assertStatus(403)
             ->assertJson(['message' => 'Forbidden. You do not have permission to access this resource.']);
    }

    public function test_admin_role_access_boundaries(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $ppic = User::factory()->create(['role' => Role::PPIC]);

        // Allowed for ADMIN
        $this->actingAs($admin, 'sanctum')
             ->getJson('/api/v1/admin-boundary-test')
             ->assertStatus(200)
             ->assertJson(['message' => 'Admin access granted']);

        // Forbidden for non-ADMIN (PPIC)
        $this->actingAs($ppic, 'sanctum')
             ->getJson('/api/v1/admin-boundary-test')
             ->assertStatus(403)
             ->assertJson(['message' => 'Forbidden. You do not have permission to access this resource.']);
    }

    public function test_ppic_role_access_boundaries(): void
    {
        $ppic = User::factory()->create(['role' => Role::PPIC]);
        $staff = User::factory()->create(['role' => Role::PRODUCTION_STAFF]);

        // Allowed for PPIC
        $this->actingAs($ppic, 'sanctum')
             ->getJson('/api/v1/ppic-boundary-test')
             ->assertStatus(200)
             ->assertJson(['message' => 'PPIC access granted']);

        // Forbidden for non-PPIC (PRODUCTION_STAFF)
        $this->actingAs($staff, 'sanctum')
             ->getJson('/api/v1/ppic-boundary-test')
             ->assertStatus(403)
             ->assertJson(['message' => 'Forbidden. You do not have permission to access this resource.']);
    }

    public function test_production_staff_role_access_boundaries(): void
    {
        $staff = User::factory()->create(['role' => Role::PRODUCTION_STAFF]);
        $admin = User::factory()->create(['role' => Role::ADMIN]);

        // Allowed for PRODUCTION_STAFF
        $this->actingAs($staff, 'sanctum')
             ->getJson('/api/v1/staff-boundary-test')
             ->assertStatus(200)
             ->assertJson(['message' => 'Staff access granted']);

        // Forbidden for non-staff (ADMIN)
        $this->actingAs($admin, 'sanctum')
             ->getJson('/api/v1/staff-boundary-test')
             ->assertStatus(403)
             ->assertJson(['message' => 'Forbidden. You do not have permission to access this resource.']);
    }

    public function test_multi_role_access_boundaries(): void
    {
        $owner = User::factory()->create(['role' => Role::OWNER]);
        $ppic = User::factory()->create(['role' => Role::PPIC]);
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $staff = User::factory()->create(['role' => Role::PRODUCTION_STAFF]);

        // Allowed for OWNER and PPIC
        $this->actingAs($owner, 'sanctum')
             ->getJson('/api/v1/multi-role-boundary-test')
             ->assertStatus(200);

        $this->actingAs($ppic, 'sanctum')
             ->getJson('/api/v1/multi-role-boundary-test')
             ->assertStatus(200);

        // Forbidden for ADMIN and PRODUCTION_STAFF
        $this->actingAs($admin, 'sanctum')
             ->getJson('/api/v1/multi-role-boundary-test')
             ->assertStatus(403);

        $this->actingAs($staff, 'sanctum')
             ->getJson('/api/v1/multi-role-boundary-test')
             ->assertStatus(403);
    }
}
