<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderSize;
use App\Models\Size;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
}
