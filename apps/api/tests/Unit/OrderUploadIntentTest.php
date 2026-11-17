<?php

namespace Tests\Unit;

use App\Enums\OrderUploadIntentStatus;
use App\Models\OrderUploadIntent;
use Tests\TestCase;

class OrderUploadIntentTest extends TestCase
{
  /**
   * A basic unit test example.
   */
  public function test_order_upload_intent_can_transition_to_uploading(): void
  {
    $intent = new OrderUploadIntent();
    $intent->status = OrderUploadIntentStatus::PENDING_UPLOAD;

    $result = $intent->transitionTo(OrderUploadIntentStatus::UPLOADING);

    $this->assertTrue($result);
    $this->assertSame(OrderUploadIntentStatus::UPLOADING, $intent->status);
  }

  public function test_order_upload_intent_rejects_invalid_transition(): void
  {
    $intent = new OrderUploadIntent();
    $intent->status = OrderUploadIntentStatus::ATTACHED;

    $result = $intent->transitionTo(OrderUploadIntentStatus::CLEANING);

    $this->assertFalse($result);
    $this->assertEquals(OrderUploadIntentStatus::ATTACHED, $intent->status);
  }

  public function test_order_upload_intent_cannot_transition_without_initial_status(): void
  {
    $intent = new OrderUploadIntent();

    $result = $intent->transitionTo(OrderUploadIntentStatus::UPLOADING);

    $this->assertFalse($result);
    $this->assertNull($intent->status);
  }
}
