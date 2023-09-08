<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('babysitters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prenom');
            $table->integer('age')->nullable();
            $table->string('genre')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('password_confirmation')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('telephone')->nullable();
            $table->string('exquestion')->nullable();
            $table->text('type_experience')->nullable();
            $table->string('experience')->nullable();
            $table->integer('age_enfants')->nullable();
            $table->text('numeros_familles')->nullable();
            $table->string('cv')->nullable();
            $table->string('certificat_secourisme')->nullable();
            $table->string('cin')->nullable();
            $table->string('attestation_presence')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('babysitters');
    }
};
