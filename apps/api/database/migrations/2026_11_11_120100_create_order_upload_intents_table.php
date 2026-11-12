<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create("order_upload_intents", function (Blueprint $table): void {
      $table->id();
      $table->uuid("request_id");
      $table->string("object_key", 1024)->unique();
      $table->string("object_version_id", 255)->nullable();
      $table->string("status", 24)->default("PENDING_UPLOAD");
      $table
        ->foreignId("order_id")
        ->nullable()
        ->constrained("orders")
        ->nullOnDelete();
      $table->string("lease_owner")->nullable();
      $table->uuid("lease_token")->nullable();
      $table->timestampTz("lease_expires_at")->nullable();
      $table->unsignedInteger("cleanup_attempts")->default(0);
      $table->timestampTz("next_retry_at")->nullable();
      $table->text("last_error")->nullable();
      $table->timestampTz("cleanup_started_at")->nullable();
      $table->timestampTz("escalated_at")->nullable();
      $table->timestampTz("uploaded_at")->nullable();
      $table->timestampTz("attached_at")->nullable();
      $table->timestampTz("cleaned_at")->nullable();
      $table->timestampsTz();

      $table->index(["request_id"]);
      $table->index(["status", "next_retry_at"]);
      $table->index(["status", "lease_expires_at"]);
    });

    if (DB::connection()->getDriverName() === "pgsql") {
      DB::statement(
        "ALTER TABLE order_upload_intents ADD CONSTRAINT chk_order_upload_intents_status CHECK (status IN ('PENDING_UPLOAD', 'UPLOADING', 'UPLOADED', 'ATTACHED', 'CLEANUP_PENDING', 'CLEANING', 'CLEANED', 'NEEDS_ATTENTION'))",
      );
      DB::statement(
        "ALTER TABLE order_upload_intents ADD CONSTRAINT chk_order_upload_intents_cleanup_attempts_non_negative CHECK (cleanup_attempts >= 0)",
      );
    }
  }

  public function down(): void
  {
    if (DB::table("order_upload_intents")->exists()) {
      throw new RuntimeException(
        "Cannot drop order_upload_intents while upload or cleanup records exist.",
      );
    }

    Schema::dropIfExists("order_upload_intents");
  }
};
