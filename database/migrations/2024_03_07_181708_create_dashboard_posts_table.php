<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primayKey = "DashboardPostID";
    public function up(): void
    {
        Schema::create('dashboard_posts', function (Blueprint $table) {
            $table->id("DashboardPostID");
            //usuario al que se hace referencia
            $table->unsignedBigInteger("UserID");
            $table->foreign("UserID")
            ->references("UserID")
            ->on("users")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //ultimo posteos que se viralizaron
            $table->unsignedBigInteger("PostID");
            $table->foreign("PostID")
            ->references("PostID")
            ->on("user_posts")
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
        Schema::dropIfExists('dashboard_posts');
    }
};
