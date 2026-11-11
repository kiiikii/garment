<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(["order_number", "customer_id", "production_type", "status"])]
class Order extends Model
{
  /** */
  protected function casts(): array
  {
    return ["status" => OrderStatus::class];
  }

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  public function orderSizes(): HasMany
  {
    return $this->hasMany(OrderSize::class);
  }

  public function orderImages(): HasMany
  {
    return $this->hasMany(OrderImage::class);
  }
}
