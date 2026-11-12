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
}
