<?php

namespace Tests\Unit;

use App\Enums\OrderUploadIntentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderSize;
use App\Models\OrderUploadIntent;
use App\Models\Size;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
  /**
   * A basic unit test example.
   */
  public function test_customer_orders_has_many(): void
  {
    $customer = new Customer();
    $relation = $customer->orders();

    $this->assertInstanceOf(HasMany::class, $relation);
    $this->assertInstanceOf(Order::class, $relation->getRelated());
    $this->assertSame("customer_id", $relation->getForeignKeyName());
  }
  public function test_order_customer_belongs_to(): void
  {
    $order = new Order();
    $relation = $order->customer();

    $this->assertInstanceOf(BelongsTo::class, $relation);
    $this->assertInstanceOf(Customer::class, $relation->getRelated());
    $this->assertSame("customer_id", $relation->getForeignKeyName());
  }
  public function test_order_order_sizes_has_many(): void
  {
    $order = new Order();
    $relation = $order->orderSizes();

    $this->assertInstanceOf(HasMany::class, $relation);
    $this->assertInstanceOf(OrderSize::class, $relation->getRelated());
    $this->assertSame("order_id", $relation->getForeignKeyName());
  }
  public function test_order_order_images_has_many(): void
  {
    $order = new Order();
    $relation = $order->orderImages();

    $this->assertInstanceOf(HasMany::class, $relation);
    $this->assertInstanceOf(OrderImage::class, $relation->getRelated());
    $this->assertSame("order_id", $relation->getForeignKeyName());
  }
  public function test_size_order_sizes_has_many(): void
  {
    $size = new Size();
    $relation = $size->orderSizes();

    $this->assertInstanceOf(HasMany::class, $relation);
    $this->assertInstanceOf(OrderSize::class, $relation->getRelated());
    $this->assertSame("size_id", $relation->getForeignKeyName());
  }
  public function test_order_size_order_belongs_to(): void
  {
    $orderSize = new OrderSize();
    $relation = $orderSize->order();

    $this->assertInstanceOf(BelongsTo::class, $relation);
    $this->assertInstanceOf(Order::class, $relation->getRelated());
    $this->assertSame("order_id", $relation->getForeignKeyName());
  }
  public function test_order_size_size_belongs_to(): void
  {
    $orderSize = new OrderSize();
    $relation = $orderSize->size();

    $this->assertInstanceOf(BelongsTo::class, $relation);
    $this->assertInstanceOf(Size::class, $relation->getRelated());
    $this->assertSame("size_id", $relation->getForeignKeyName());
  }
  public function test_order_image_order_belongs_to(): void
  {
    $orderImage = new OrderImage();
    $relation = $orderImage->order();

    $this->assertInstanceOf(BelongsTo::class, $relation);
    $this->assertInstanceOf(Order::class, $relation->getRelated());
    $this->assertSame("order_id", $relation->getForeignKeyName());
  }

  public function test_order_has_many_order_upload_intents(): void
  {
    $order = new Order();
    $relation = $order->orderUploadIntents();

    $this->assertInstanceOf(HasMany::class, $relation);
    $this->assertInstanceOf(OrderUploadIntent::class, $relation->getRelated());
    $this->assertSame("order_id", $relation->getForeignKeyName());
  }

  public function test_order_uplaod_intent_belong_to_order(): void
  {
    $orderUploadIntent = new OrderUploadIntent();
    $relation = $orderUploadIntent->order();

    $this->assertInstanceOf(BelongsTo::class, $relation);
    $this->assertInstanceOf(Order::class, $relation->getRelated());
    $this->assertSame("order_id", $relation->getForeignKeyName());
  }

  public function test_order_upload_intent_status_is_cast_to_enum(): void
  {
    $expectedStatus = OrderUploadIntentStatus::PENDING_UPLOAD;

    $intent = new OrderUploadIntent();
    $intent->status = $expectedStatus;

    $this->assertInstanceOf(OrderUploadIntentStatus::class, $intent->status);
    $this->assertSame($expectedStatus, $intent->status);
  }

  public function test_order_upload_intent_cleanup_attempts_is_cast_to_integer(): void
  {
    $intent = new OrderUploadIntent();
    $intent->setRawAttributes([
      "cleanup_attempts" => "5",
    ]);

    $this->assertIsInt($intent->cleanup_attempts);
    $this->assertSame(5, $intent->cleanup_attempts);
  }

  public function test_order_upload_intent_datetime_attributes_are_cast_to_carbon(): void
  {
    $datetime = new OrderUploadIntent();
    $datetime->setRawAttributes([
      "lease_expires_at" => "2026-11-13 10:32:00",
      "next_retry_at" => "2026-11-13 10:32:00",
      "cleanup_started_at" => "2026-11-13 10:32:00",
      "escalated_at" => "2026-11-13 10:32:00",
      "uploaded_at" => "2026-11-13 10:32:00",
      "attached_at" => "2026-11-13 10:32:00",
      "cleaned_at" => "2026-11-13 10:32:00",
    ]);

    $this->assertInstanceOf(Carbon::class, $datetime->lease_expires_at);
    $this->assertInstanceOf(Carbon::class, $datetime->next_retry_at);
    $this->assertInstanceOf(Carbon::class, $datetime->cleanup_started_at);
    $this->assertInstanceOf(Carbon::class, $datetime->escalated_at);
    $this->assertInstanceOf(Carbon::class, $datetime->uploaded_at);
    $this->assertInstanceOf(Carbon::class, $datetime->attached_at);
    $this->assertInstanceOf(Carbon::class, $datetime->cleaned_at);
  }
}
