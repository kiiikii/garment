<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderStatusTest extends TestCase
{
  /**
   * A basic unit test example.
   */
  public function test_order_status_enum_has_expected_values(): void
  {
    $cases = OrderStatus::cases();

    $this->assertCount(7, $cases);
    $this->assertSame(
      [
        "INTAKE",
        "SAMPLING",
        "LOA_PENDING",
        "PRODUCTION_READINESS",
        "MASS_PRODUCTION",
        "COMPLETED",
        "CANCELLED",
      ],
      array_map(
        fn(OrderStatus $status) => $status->value,
        OrderStatus::cases(),
      ),
    );
  }
}
