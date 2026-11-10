<?php

namespace App\Enums;

enum Role: string{
  case OWNER = 'OWNER';
  case ADMIN = 'ADMIN';
  case PPIC = 'PPIC';
  case PRODUCTION_STAFF = 'PRODUCTION_STAFF';
};