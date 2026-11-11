<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(["order_id", "file_path", "sort_order"])]
class OrderImage extends Model
{
  public const UPDATED_AT = null;
  protected function casts(): array
  {
    return ["sort_order" => "integer"];
  }

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }
}
