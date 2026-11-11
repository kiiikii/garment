<?php

namespace App\Enums;

enum OrderStatus: string
{
  case INTAKE = "INTAKE";
  case SAMPLING = "SAMPLING";
  case LOA_PENDING = "LOA_PENDING";
  case PRODUCTION_READINESS = "PRODUCTION_READINESS";
  case MASS_PRODUCTION = "MASS_PRODUCTION";
  case COMPLETED = "COMPLETED";
  case CANCELLED = "CANCELLED";
}
