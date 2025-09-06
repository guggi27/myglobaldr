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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->foreign("doctor_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->foreign("patient_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->text("call_id")->nullable();
            $table->longText("message")->nullable();            
            $table->enum("type", ["audio", "video"]);
            $table->enum("status", ["calling", "accepted", "rejected", "completed"]);
            $table->datetime("start")->nullable();
            $table->datetime("end")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
