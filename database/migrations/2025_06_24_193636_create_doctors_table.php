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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onUpdate("CASCADE")->onDelete("CASCADE");
            $table->json("services")->nullable();
            $table->json("specialities")->nullable();
            $table->json("diseases")->nullable();
            $table->double("fee")->default(0);
            $table->string("location")->nullable();
            $table->unsignedBigInteger("reviews")->default(0);
            $table->double("ratings")->default(0);
            $table->enum("status", ["available", "unavailable"])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
