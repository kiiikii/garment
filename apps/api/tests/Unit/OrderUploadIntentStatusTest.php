<?php

namespace Tests\Unit;

use App\Enums\OrderUploadIntentStatus;
use PHPUnit\Framework\TestCase;

class OrderUploadIntentStatusTest extends TestCase
{
  /**
   * A basic unit test example.
   */
  public function test_order_upload_intent_status(): void
  {
    $this->assertCount(8, OrderUploadIntentStatus::cases());
  }

  public function test_order_upload_intent_status_has_expected_values(): void
  {
    $cases = OrderUploadIntentStatus::cases();

    $this->assertSame(
      [
        "PENDING_UPLOAD",
        "UPLOADING",
        "UPLOADED",
        "ATTACHED",
        "CLEANUP_PENDING",
        "CLEANING",
        "CLEANED",
        "NEEDS_ATTENTION",
      ],
      array_map(fn(OrderUploadIntentStatus $status) => $status->value, $cases),
    );
  }

  public function test_order_upload_intent_status_has_no_duplicate_values(): void
  {
    $cases = array_column(OrderUploadIntentStatus::cases(), "value");

    $unique = array_values(array_unique($cases));

    $this->assertSame(count($cases), count($unique));
  }

  public function test_all_allowed_transitions_are_permitted(): void
  {
    $this->assertTrue(
      OrderUploadIntentStatus::PENDING_UPLOAD->canTransitionTo(
        OrderUploadIntentStatus::UPLOADING,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::UPLOADING->canTransitionTo(
        OrderUploadIntentStatus::UPLOADED,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::UPLOADING->canTransitionTo(
        OrderUploadIntentStatus::CLEANUP_PENDING,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::UPLOADED->canTransitionTo(
        OrderUploadIntentStatus::ATTACHED,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::UPLOADED->canTransitionTo(
        OrderUploadIntentStatus::CLEANUP_PENDING,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::CLEANUP_PENDING->canTransitionTo(
        OrderUploadIntentStatus::CLEANING,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::CLEANING->canTransitionTo(
        OrderUploadIntentStatus::CLEANED,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::CLEANING->canTransitionTo(
        OrderUploadIntentStatus::CLEANUP_PENDING,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::CLEANING->canTransitionTo(
        OrderUploadIntentStatus::NEEDS_ATTENTION,
      ),
    );
    $this->assertTrue(
      OrderUploadIntentStatus::NEEDS_ATTENTION->canTransitionTo(
        OrderUploadIntentStatus::CLEANING,
      ),
    );
  }

  public function test_terminal_states_cannot_transition_to_cleaning(): void
  {
    $this->assertFalse(
      OrderUploadIntentStatus::ATTACHED->canTransitionTo(
        OrderUploadIntentStatus::CLEANING,
      ),
    );
    $this->assertFalse(
      OrderUploadIntentStatus::CLEANED->canTransitionTo(
        OrderUploadIntentStatus::CLEANING,
      ),
    );
  }

  public function test_invalid_transitions_are_rejected(): void
  {
    $this->assertFalse(
      OrderUploadIntentStatus::PENDING_UPLOAD->canTransitionTo(
        OrderUploadIntentStatus::UPLOADED,
      ),
    );
    $this->assertFalse(
      OrderUploadIntentStatus::UPLOADING->canTransitionTo(
        OrderUploadIntentStatus::ATTACHED,
      ),
    );
    $this->assertFalse(
      OrderUploadIntentStatus::CLEANUP_PENDING->canTransitionTo(
        OrderUploadIntentStatus::CLEANED,
      ),
    );
    $this->assertFalse(
      OrderUploadIntentStatus::NEEDS_ATTENTION->canTransitionTo(
        OrderUploadIntentStatus::CLEANUP_PENDING,
      ),
    );
  }
}
