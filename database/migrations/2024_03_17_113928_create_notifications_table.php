<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = "NotificationID";
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id("NotificationID");
            //link de la notificacion
            $table->string("Link");
            //usuario al que le pertenece la notificacion
            $table->unsignedBigInteger("UserID");

            $table->foreign("UserID")
            ->references("PersonalDataID")
            ->on("personal_data")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");

            //en caso de que sea una notifiacion a un posteo,posteo al que hace referencia
            $table->unsignedBigInteger("PostID")->nullable();
            $table->foreign("PostID")
            ->references("PostID")
            ->on("user_posts")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");
            
            //tipo de notificacion
            $table->enum("Type",["Like","comment","follow","setting"]);

            //ultimo usuario que ha comentado,likeado o seguido
            $table->unsignedBigInteger("LastUserAction")->nullable();
            $table->foreign("LastUserAction")
            ->references("PersonalDataID")
            ->on("personal_data")
            ->onDelete("CASCADE")
            ->onUpdate("CASCADE");
            
            //contenido de la notificacion,mensage
            $table->string("Content");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
