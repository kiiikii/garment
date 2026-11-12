<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPersistenceTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   */
  public function test_customer_can_be_persisted(): void
  {
    $customer = Customer::create([
      "name" => "budi santoso",
      "phone" => "08123456789",
      "origin_address" => "Kebak Kramat",
    ]);

    $this->assertDatabaseHas("customers", [
      "id" => $customer->id,
      "name" => $customer->name,
      "phone" => $customer->phone,
      "origin_address" => $customer->origin_address,
    ]);
  }

  public function test_order_can_be_persisted_with_customer(): void
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

    $this->assertDatabaseHas("orders", [
      "id" => $order->id,
      "order_number" => $order->order_number,
      "customer_id" => $customer->id,
      "production_type" => $order->production_type,
      "status" => $order->status->value,
    ]);

    $this->assertSame($customer->id, $order->customer->id);
  }

  public function test_order_size_can_be_persisted_with_order_and_size(): void
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

    $size = Size::create([
      "name" => "S",
      "type" => "STANDARD",
      "is_active" => true,
    ]);

    $orderSize = $order->orderSizes()->create([
      "size_id" => $size->id,
      "quantity" => 20,
    ]);

    $this->assertDatabaseHas("order_sizes", [
      "id" => $orderSize->id,
      "order_id" => $order->id,
      "size_id" => $size->id,
      "quantity" => $orderSize->quantity,
    ]);

    $this->assertTrue($order->orderSizes->contains($orderSize));
    $this->assertTrue($size->orderSizes->contains($orderSize));
  }

  public function test_order_image_can_be_persisted_with_order(): void
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

    $orderImage = $order->orderImages()->create([
      "file_path" => "orders/ORD-001/front.png",
      "sort_order" => 0,
    ]);

    $this->assertDatabaseHas("order_images", [
      "id" => $orderImage->id,
      "order_id" => $order->id,
      "file_path" => $orderImage->file_path,
      "sort_order" => $orderImage->sort_order,
    ]);

    $this->assertTrue($order->orderImages->contains($orderImage));
    $this->assertNotNull($orderImage->created_at);
  }
}
