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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('bib')->unique();
            // informasi umum
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            // biodata
            $table->string('community')->nullable();
            $table->enum('gender', ['female', 'male'])->nullable();
            $table->string('nik')->nullable();
            $table->string('birthplace')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('jersey_size')->nullable();
            // informasi kesehatan
            $table->string('blood_type')->nullable();
            $table->string('medical_history')->nullable();
            $table->string('medical_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
