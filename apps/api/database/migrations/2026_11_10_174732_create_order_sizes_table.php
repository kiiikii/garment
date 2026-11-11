<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create("order_sizes", function (Blueprint $table) {
      $table->id();
      $table->foreignId("order_id")->constrained("orders")->onDelete("cascade");
      $table->foreignId("size_id")->constrained("sizes")->onDelete("restrict");
      $table->integer("quantity");
      $table->unique(["order_id", "size_id"]);
      $table->index("size_id");
      $table->timestamps();
    });

    if (DB::connection()->getDriverName() === "pgsql") {
      DB::statement(
        "ALTER TABLE order_sizes ADD CONSTRAINT chk_order_sizes_quantity_non_negative CHECK (quantity >= 0)",
      );
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("order_sizes");
  }
};
