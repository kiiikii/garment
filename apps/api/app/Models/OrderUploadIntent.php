<?php

namespace App\Models;

use App\Enums\OrderUploadIntentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(["request_id", "object_key"])]
class OrderUploadIntent extends Model
{
  protected function casts(): array
  {
    return [
      "status" => OrderUploadIntentStatus::class,
      "cleanup_attempts" => "integer",
      "lease_expires_at" => "datetime",
      "next_retry_at" => "datetime",
      "cleanup_started_at" => "datetime",
      "escalated_at" => "datetime",
      "uploaded_at" => "datetime",
      "attached_at" => "datetime",
      "cleaned_at" => "datetime",
    ];
  }

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }
}
