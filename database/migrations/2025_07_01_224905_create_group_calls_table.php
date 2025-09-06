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
        Schema::create('group_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->foreign("patient_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->text("call_id")->nullable();
            $table->json("diseases")->nullable();
            $table->enum("type", ["audio", "video"])->nullable();
            $table->enum("status", ["created", "completed"])->default("created");
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
        Schema::dropIfExists('group_calls');
    }
};
