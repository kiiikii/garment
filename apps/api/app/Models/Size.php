<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(["name", "type", "is_active"])]
class Size extends Model
{
  //
  protected function casts(): array
  {
    return ["is_active" => "boolean"];
  }

  public function orderSizes(): HasMany
  {
    return $this->hasMany(OrderSize::class);
  }
}
