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
    Schema::create("orders", function (Blueprint $table) {
      $table->id();
      $table->string("order_number", 30)->unique();
      $table
        ->foreignId("customer_id")
        ->constrained("customers")
        ->onDelete("restrict");
      $table->index("customer_id");
      $table->string("production_type", 10);
      $table->string("status", 30)->default("INTAKE");
      $table->timestamps();
    });

    if (DB::connection()->getDriverName() === "pgsql") {
      DB::statement(
        "ALTER TABLE orders ADD CONSTRAINT chk_orders_production_type_allowed CHECK (production_type IN ('CMT', 'FOB'))",
      );
      DB::statement(
        "ALTER TABLE orders ADD CONSTRAINT chk_orders_status_type_allowed CHECK (status IN ('INTAKE', 'SAMPLING', 'LOA_PENDING', 'PRODUCTION_READINESS', 'MASS_PRODUCTION', 'COMPLETED', 'CANCELLED'))",
      );
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists("orders");
  }
};
