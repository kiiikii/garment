<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   */
  public function test_login_fails_with_invalid_credentials(): void
  {
    $response = $this->postJson("api/v1/auth/login", [
      "email" => "invalid@example.com",
      "password" => "wrong-password",
    ]);

    $response->assertStatus(401)->assertJson([
      "message" => "Invalid credentials",
    ]);
  }

  public function test_login_succeeds_with_valid_credentials(): void
  {
    $user = User::factory()->create([
      "password" => bcrypt("password"),
    ]);
    $response = $this->postJson("api/v1/auth/login", [
      "email" => $user->email,
      "password" => "password",
    ]);

    $response
      ->assertStatus(200)
      ->assertJsonStructure([
        "token",
        "user" => ["id", "name", "email", "role"],
      ]);
  }

  public function test_login_requires_valid_input(): void
  {
    $response = $this->postJson("api/v1/auth/login", [
      "email" => "",
      "password" => "",
    ]);

    $response
      ->assertStatus(422)
      ->assertJsonValidationErrors(["email", "password"]);
  }

  public function test_me_requires_authentication(): void
  {
    $response = $this->getJson("/api/v1/auth/me");
    $response->assertStatus(401)->assertJson(["message" => "Unauthenticated."]);
  }

  public function test_authenticated_user_can_view_profile(): void
  {
    $user = User::factory()->create();
    $token = $user->createToken("test-token")->plainTextToken;

    $response = $this->withToken($token)->getJson("/api/v1/auth/me");
    $response->assertStatus(200)->assertJson([
      "data" => [
        "id" => $user->id,
        "email" => $user->email,
        "role" => $user->role->value,
      ],
    ]);
  }

  public function test_logout_revokes_current_token(): void
  {
    $user = User::factory()->create();
    $token = $user->createToken("test-token")->plainTextToken;

    $response = $this->withToken($token);
    $response = $this->withToken($token)->postJson("/api/v1/auth/logout");
    $response->assertStatus(200)->assertJson([
      "message" => "Successfully logged out",
    ]);
  }
}
