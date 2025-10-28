<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Routenpunkte;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('routen', function (Blueprint $table) {
            $table->id();
            $table->string('fahrzeug_id');
            $table->string('startort_id', 50);
            $table->string('zielort_id', 50);
            $table->foreign('startort_id')->references('lagerplatz_id')->on('lagerplaetze');
            $table->foreign('zielort_id')->references('lagerplatz_id')->on('lagerplaetze');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routen');
    }

};
