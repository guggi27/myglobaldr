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
        Schema::create('group_call_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("group_call_id")->nullable();
            $table->foreign("group_call_id")->references("id")->on("group_calls")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->foreign("doctor_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->longText("message")->nullable();
            $table->enum("status", ["sent", "accepted", "rejected", "joined", "left"])->default("sent");
            $table->datetime("joined_at")->nullable();
            $table->datetime("left_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_call_participants');
    }
};
