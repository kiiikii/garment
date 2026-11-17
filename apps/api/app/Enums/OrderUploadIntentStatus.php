<?php

namespace App\Enums;

enum OrderUploadIntentStatus: string
{
  case PENDING_UPLOAD = "PENDING_UPLOAD";
  case UPLOADING = "UPLOADING";
  case UPLOADED = "UPLOADED";
  case ATTACHED = "ATTACHED";
  case CLEANUP_PENDING = "CLEANUP_PENDING";
  case CLEANING = "CLEANING";
  case CLEANED = "CLEANED";
  case NEEDS_ATTENTION = "NEEDS_ATTENTION";

  public function canTransitionTo(self $nextStatus): bool
  {
    return match ([$this, $nextStatus]) {
      [self::PENDING_UPLOAD, self::UPLOADING],
      [self::PENDING_UPLOAD, self::CLEANUP_PENDING],
      [self::UPLOADING, self::UPLOADED],
      [self::UPLOADING, self::CLEANUP_PENDING],
      [self::UPLOADED, self::ATTACHED],
      [self::UPLOADED, self::CLEANUP_PENDING],
      [self::CLEANUP_PENDING, self::CLEANING],
      [self::CLEANING, self::CLEANED],
      [self::CLEANING, self::CLEANUP_PENDING],
      [self::CLEANING, self::NEEDS_ATTENTION],
      [self::NEEDS_ATTENTION, self::CLEANING]
        => true,

      default => false,
    };
  }
}
