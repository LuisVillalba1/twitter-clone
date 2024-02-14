<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "SaveID";

    public function up(): void
    {
        Schema::create('save_posts', function (Blueprint $table) {
            //llave primaria de los post guardados
            $table->id("SaveID");
            //usuario que ha guardado el post
            $table->unsignedBigInteger("UserID");
            $table->foreign("UserID")
            ->references("UserID")
            ->on("users")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //post que ha guardado el usuario
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
        Schema::dropIfExists('save_posts');
    }
};
