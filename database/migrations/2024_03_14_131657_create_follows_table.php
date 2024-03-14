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
        Schema::create('follows', function (Blueprint $table) {
            $table->id("FollowID");
            //hace referencia a quien realiza el seguimiento
            $table->unsignedBigInteger("FollowUserID");
            $table->foreign("FollowUserID")
            ->references("PersonalDataID")
            ->on("personal_data")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //seguidores que contiene un usuario
            $table->unsignedBigInteger("FollowerID");
            $table->foreign("FollowerID")
            ->references("PersonalDataID")
            ->on("personal_data")
            ->onUpdate("CASCADE")
            ->onDelete("CASCADE");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
