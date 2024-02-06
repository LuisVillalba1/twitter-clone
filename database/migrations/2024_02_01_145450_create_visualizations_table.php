<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "VisualizationID";
    public function up(): void
    {
        Schema::create('visualizations', function (Blueprint $table) {
            $table->id("VisualizationID");
            $table->unsignedBigInteger("PostID");

            //a que interaccion es correspondiente la visualizacion
            $table->foreign("PostID")
            ->references("PostID")
            ->on("user_posts")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //el usuario que realizo la visualizacion
            $table->unsignedBigInteger("NicknameID");
            $table->foreign("NicknameID")
            ->references("PersonalDataID")
            ->on("personal_data")
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
        Schema::dropIfExists('visualizations');
    }
};
