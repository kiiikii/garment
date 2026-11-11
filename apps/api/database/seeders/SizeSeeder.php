<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $size = ["S", "M", "L", "XL"];

    foreach ($size as $value) {
      DB::table("sizes")->insertOrIgnore([
        "name" => $value,
        "type" => "STANDARD",
        "is_active" => true,
        "created_at" => now(),
        "updated_at" => now(),
      ]);
    }
  }
}
