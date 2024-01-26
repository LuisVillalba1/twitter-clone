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
        Schema::create('user_posts', function (Blueprint $table) {
            $table->id("PostID");
            $table->string("Message");
            $table->unsignedBigInteger("MultimediaID");

            $table->foreign("MultimediaID")
            ->references("MultimediaID")
            ->on("multimedia_posts")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_posts');
    }
};
