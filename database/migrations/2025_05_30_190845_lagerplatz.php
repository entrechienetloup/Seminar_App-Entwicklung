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
        Schema::create('lagerplaetze', function (Blueprint $table) {
            $table->string('lagerplatz_id', 50)->primary();
            $table->string('type');
            $table->integer('x');
            $table->integer('y');
            $table->integer('breite')->default('38');
            $table->integer('hoehe')->default('35');
            $table->integer('anzahl')->default('9');
            $table->integer('belegt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lagerplaetze');
    }
};
