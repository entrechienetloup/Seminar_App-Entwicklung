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
        Schema::create('fahrzeuge', function (Blueprint $table) {
            $table->string('fahrzeug_id')->primary();
            $table->string('type')->nullable();
            $table->string('transportauftrag_id')->nullable();
            $table->string('status')->nullable(); 
            $table->string('zeitstempel')->nullable(); 
            $table->string('ladestand')->nullable(); 
            $table->string('akkuzustand')->nullable(); 
            $table->string('akt_ta')->nullable(); 
            $table->string('meldung')->default('Keine');
            $table->integer('x');
            $table->integer('y');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fahrzeuge');
    }
};
