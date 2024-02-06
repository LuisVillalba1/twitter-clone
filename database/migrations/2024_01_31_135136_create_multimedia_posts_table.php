<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "MultiMediaID";
    public function up(): void
    {
        Schema::create('multimedia_posts', function (Blueprint $table) {
            $table->id("MultimediaID");
            $table->string("Name");
            $table->text("Url");
            //post al que hace referencia el contenido multimedia
            $table->unsignedBigInteger("PostID")->nullable();

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
        Schema::dropIfExists('multimedia_posts');
    }
};
