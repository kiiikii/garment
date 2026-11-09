<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthTest extends TestCase
{
  /**
    * A basic feature test example.
  **/
  public function test_health_endpoint_returns_ok_status(): void {
    $response = $this->get('/health');

    $response->assertStatus(200)->assertJson(['status'=>'ok']);
  }

  public function test_api_not_found_returns_json_404(): void {
    $response = $this->getJson('/api/v1/non-exsistent-route');

    $response->assertStatus(404)->assertJson(['message'=>'Resource not found']);
  }
}
