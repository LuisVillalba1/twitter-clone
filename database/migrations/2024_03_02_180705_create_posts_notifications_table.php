<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "PostNotificationID";

    public function up(): void
    {
        Schema::create('posts_notifications', function (Blueprint $table) {
            $table->id("PostNotificationID");

            //propietario de la notificacion
            $table->unsignedBigInteger("UserID");
            $table->foreign("UserID")
            ->references("UserID")
            ->on("users")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //post al que se hace referencia
            $table->unsignedBigInteger("PostID");
            $table->foreign("PostID")
            ->references("PostID")
            ->on("user_posts")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //link del post
            $table->string("LinkPost");

            //usuario que realizo la accion
            $table->unsignedBigInteger("ActionUserID");
            $table->foreign("ActionUserID")
            ->references("UserID")
            ->on("users")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //que tipo de accion se realizo, ya sea un like,comentario, repost etc
            $table->string("Action");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_notifications');
    }
};
