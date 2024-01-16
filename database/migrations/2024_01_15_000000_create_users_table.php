<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    protected $primaryKey = "UserID";

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id("UserID");
            $table->string('Name');
            $table->string('Email')->unique();
            $table->string('Password');
            //personal data del usuario
            $table->unsignedBigInteger("PersonalDataID");
            $table->foreign("PersonalDataID")
            ->references("PersonalDataID")
            ->on("personal_data")
            ->onDelete("cascade")
            ->onUpdate("cascade");

            //aqui almacenaremos el codigo de verificacion
            $table->unsignedBigInteger("VerificationID");
            $table->foreign("VerificationID")
            ->references("VerificationID")
            ->on("verification_accounts")
            ->onDelete("cascade")
            ->onUpdate("cascade");

            $table->boolean("VerifiedMail")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
