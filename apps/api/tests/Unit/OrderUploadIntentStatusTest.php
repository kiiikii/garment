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
}
