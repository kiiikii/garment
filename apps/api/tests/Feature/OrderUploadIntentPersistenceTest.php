<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\OrderUploadIntentStatus;
use App\Models\Customer;
use App\Models\OrderUploadIntent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderUploadIntentPersistenceTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   */
  public function test_order_upload_intent_can_be_persisted(): void
  {
    $requestUuid = Str::uuid()->toString();
    $objectKey = "orders/ORD-001/front.png";

    $intent = OrderUploadIntent::create([
      "request_id" => $requestUuid,
      "object_key" => $objectKey,
    ]);

    $this->assertDatabaseHas("order_upload_intents", [
      "id" => $intent->id,
      "request_id" => $requestUuid,
      "object_key" => $objectKey,
      "status" => OrderUploadIntentStatus::PENDING_UPLOAD->value,
      "cleanup_attempts" => 0,
      "order_id" => null,
    ]);
  }

  public function test_order_upload_intent_can_be_persisted_with_order(): void
  {
    $customer = Customer::create([
      "name" => "budi santoso",
      "phone" => "08123456789",
      "origin_address" => "Kebak Kramat",
    ]);

    $order = $customer->orders()->create([
      "order_number" => "ORD-001",
      "production_type" => "CMT",
      "status" => OrderStatus::INTAKE,
    ]);

    $requestUuid = Str::uuid()->toString();
    $objectKey = "orders/ORD-001/front.png";

    $uploadIntent = $order->orderUploadIntents()->create([
      "request_id" => $requestUuid,
      "object_key" => $objectKey,
    ]);

    $this->assertDatabaseHas("order_upload_intents", [
      "id" => $uploadIntent->id,
      "request_id" => $requestUuid,
      "object_key" => $objectKey,
      "order_id" => $order->id,
      "status" => OrderUploadIntentStatus::PENDING_UPLOAD->value,
      "cleanup_attempts" => 0,
    ]);

    $this->assertSame($order->id, $uploadIntent->order->id);
  }

  public function test_order_upload_intent_is_hydrated_with_casted_attributes(): void
  {
    $requestUuid = Str::uuid()->toString();
    $objectKey = "orders/ORD-001/front.png";

    $orderUploadIntent = OrderUploadIntent::create([
      "request_id" => $requestUuid,
      "object_key" => $objectKey,
    ]);

    $hydrated = OrderUploadIntent::findOrFail($orderUploadIntent->id);

    $this->assertInstanceOf(OrderUploadIntentStatus::class, $hydrated->status);
    $this->assertSame(
      OrderUploadIntentStatus::PENDING_UPLOAD,
      $hydrated->status,
    );
    $this->assertSame(0, $hydrated->cleanup_attempts);
    $this->assertIsInt($hydrated->cleanup_attempts);
  }
}
