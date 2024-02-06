<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "PostID";
    public function up(): void
    {
        Schema::create('user_posts', function (Blueprint $table) {
            $table->id("PostID");
            //Usuario al que hace referencia el post
            $table->unsignedBigInteger("UserID");

            $table->foreign("UserID")
            ->references("UserID")
            ->on("users")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //cada posteo puede contar o no con un parentID, esto hace referencia a los comentarios
            $table->unsignedBigInteger("ParentID")->nullable();

            $table->foreign("ParentID")
            ->references("PostID")
            ->on("user_posts")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            $table->string("Message")->nullable();

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
