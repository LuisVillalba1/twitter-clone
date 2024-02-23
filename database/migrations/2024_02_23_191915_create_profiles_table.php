<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "ProfileID";
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id("ProfileID");
            $table->unsignedBigInteger("UserID");
            
            $table->foreign("UserID")
            ->references("UserID")
            ->on("users")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //biografia del usuario
            $table->string("Biography")->nullable();
            //links de las fotos de portada y perfil
            $table->string("CoverPhotoURL")->nullable();
            $table->string("ProfilePhotoURL")->nullable();

            //nombre de las fotos
            $table->string("CoverPhotoName")->nullable();
            $table->string("ProfilePhotoName")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
