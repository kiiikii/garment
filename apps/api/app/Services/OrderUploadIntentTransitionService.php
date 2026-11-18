<?php

namespace App\Services;

use App\Enums\OrderUploadIntentStatus;
use App\Models\OrderUploadIntent;
use Illuminate\Support\Facades\DB;

class OrderUploadIntentTransitionService
{
  public function transition(
    OrderUploadIntent $intent,
    OrderUploadIntentStatus $nextStatus,
  ): bool {
    return DB::transaction(function () use ($intent, $nextStatus): bool {
      $lockedIntent = OrderUploadIntent::whereKey($intent->id)
        ->lockForUpdate()
        ->firstOrFail();

      if (!$lockedIntent->transitionTo($nextStatus)) {
        return false;
      }

      return $lockedIntent->save();
    });
  }
}
