<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "CommentID";
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id("CommentID");
            //hace referencia a que interaccion corresponde
            $table->unsignedBigInteger("InteractionID");

            $table->foreign("InteractionID")
            ->references("InteractionID")
            ->on("posts_interactions")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");
            $table->timestamps();

            //mensajes anidados
            $table->unsignedBigInteger("ParentID")->nullable();
            $table->foreign("ParentiD")
            ->references("CommentID")
            ->on("comments")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //usuario que realiza el comentario
            $table->unsignedBigInteger("NicknameID")->nullable();
            $table->foreign("NicknameID")
            ->references("PersonalDataID")
            ->on("personal_data")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
