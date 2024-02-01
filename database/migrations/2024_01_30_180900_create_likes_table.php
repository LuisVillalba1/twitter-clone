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
        Schema::create('likes', function (Blueprint $table) {
            $table->id("LikeID");
            $table->unsignedBigInteger("InteractionID");

            $table->foreign("InteractionID")
            ->references("InteractionID")
            ->on("posts_interactions")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            $table->unsignedBigInteger("NicknameID");
            
            $table->foreign("NicknameID")
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
        Schema::dropIfExists('likes');
    }
};
