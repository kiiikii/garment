<?php

namespace Tests\Feature;

use App\Enums\OrderUploadIntentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\OrderUploadIntentTransitionService;
use App\Models\OrderUploadIntent;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderUploadIntentTransitionTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   */
  public function test_allowed_transition_is_persisted_atomically(): void
  {
    $uploadIntent = OrderUploadIntent::create([
      "request_id" => Str::uuid()->toString(),
      "object_key" => "orders/test-" . Str::uuid() . "/front.png",
    ]);

    $service = app(OrderUploadIntentTransitionService::class);

    $result = $service->transition(
      $uploadIntent,
      OrderUploadIntentStatus::UPLOADING,
    );

    $this->assertTrue($result);

    $freshUploadIntent = OrderUploadIntent::findOrFail($uploadIntent->id);
    $this->assertSame(
      OrderUploadIntentStatus::UPLOADING,
      $freshUploadIntent->status,
    );
  }

  public function test_invalid_transition_does_not_change_persisted_status(): void
  {
    $uploadIntent = OrderUploadIntent::create([
      "request_id" => Str::uuid()->toString(),
      "object_key" => "orders/test-" . Str::uuid() . "/front.png",
    ]);

    $uploadIntent->status = OrderUploadIntentStatus::ATTACHED;
    $uploadIntent->save();

    $service = app(OrderUploadIntentTransitionService::class);

    $result = $service->transition(
      $uploadIntent,
      OrderUploadIntentStatus::CLEANING,
    );

    $this->assertFalse($result);

    $freshUploadIntent = OrderUploadIntent::findOrFail($uploadIntent->id);
    $this->assertSame(
      OrderUploadIntentStatus::ATTACHED,
      $freshUploadIntent->status,
    );
  }

  public function test_stale_model_cannot_overwrite_newer_database_status(): void
  {
    $uploadIntent = OrderUploadIntent::create([
      "request_id" => Str::uuid()->toString(),
      "object_key" => "orders/test-" . Str::uuid() . "/front.png",
    ]);

    $modelA = OrderUploadIntent::findOrFail($uploadIntent->id);
    $modelB = OrderUploadIntent::findOrFail($uploadIntent->id);

    $firstOne = $modelB->transitionTo(OrderUploadIntentStatus::UPLOADING);
    $this->assertTrue($firstOne);

    $secondOne = $modelB->transitionTo(OrderUploadIntentStatus::UPLOADED);
    $this->assertTrue($secondOne);

    $modelB->save();

    $service = app(OrderUploadIntentTransitionService::class);

    $result = $service->transition($modelA, OrderUploadIntentStatus::UPLOADING);

    $this->assertFalse($result);
    $freshUploadIntent = OrderUploadIntent::findOrFail($uploadIntent->id);
    $this->assertSame(
      OrderUploadIntentStatus::UPLOADED,
      $freshUploadIntent->status,
    );
  }
}
