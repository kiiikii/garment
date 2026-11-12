<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    if (DB::connection()->getDriverName() === "pgsql") {
      $invalidQuantityCount = DB::table("order_sizes")
        ->where("quantity", "<", 1)
        ->count();

      if ($invalidQuantityCount > 0) {
        throw new RuntimeException(
          "Cannot require order size quantities of at least 1: {$invalidQuantityCount} existing row(s) have quantity below 1.",
        );
      }
    }

    Schema::table("orders", function ($table): void {
      $table->string("customer_name_snapshot", 255)->nullable();
      $table->string("customer_phone_snapshot", 50)->nullable();
      $table->text("customer_origin_address_snapshot")->nullable();
    });

    if (DB::connection()->getDriverName() === "pgsql") {
      DB::statement(
        "ALTER TABLE order_sizes DROP CONSTRAINT chk_order_sizes_quantity_non_negative",
      );
      DB::statement(
        "ALTER TABLE order_sizes ADD CONSTRAINT chk_order_sizes_quantity_minimum_one CHECK (quantity >= 1)",
      );
    }
  }

  public function down(): void
  {
    $hasCustomerSnapshots = DB::table("orders")
      ->whereNotNull("customer_name_snapshot")
      ->orWhereNotNull("customer_phone_snapshot")
      ->orWhereNotNull("customer_origin_address_snapshot")
      ->exists();

    if ($hasCustomerSnapshots) {
      throw new RuntimeException(
        "Cannot roll back customer snapshot columns while order snapshot data exists.",
      );
    }

    if (DB::connection()->getDriverName() === "pgsql") {
      DB::statement(
        "ALTER TABLE order_sizes DROP CONSTRAINT chk_order_sizes_quantity_minimum_one",
      );
      DB::statement(
        "ALTER TABLE order_sizes ADD CONSTRAINT chk_order_sizes_quantity_non_negative CHECK (quantity >= 0)",
      );
    }

    Schema::table("orders", function ($table): void {
      $table->dropColumn([
        "customer_name_snapshot",
        "customer_phone_snapshot",
        "customer_origin_address_snapshot",
      ]);
    });
  }
};
