<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("doctor_user_id")->nullable();
            $table->foreign("doctor_user_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("SET NULL");
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("symptoms")->nullable();
            $table->text("reason_for_visit")->nullable();
            $table->json("attachments")->nullable();
            $table->string("type")->nullable();
            $table->json("slot")->nullable();
            $table->json("services")->nullable();
            $table->double("fee")->default(0);
            $table->double("discount")->default(0);
            $table->double("total")->default(0);
            $table->string("payment_status")->nullable();
            $table->string("status")->nullable();
            $table->unsignedBigInteger("call_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
