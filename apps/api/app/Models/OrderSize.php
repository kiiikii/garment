<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(["order_id", "size_id", "quantity"])]
class OrderSize extends Model
{
  protected function casts(): array
  {
    return ["quantity" => "integer"];
  }

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }

  public function size(): BelongsTo
  {
    return $this->belongsTo(Size::class);
  }
}
