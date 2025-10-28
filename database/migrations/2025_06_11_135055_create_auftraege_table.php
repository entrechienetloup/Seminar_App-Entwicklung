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
        Schema::create('auftraege', function (Blueprint $table) {
            $table->string('transportauftrag_id')->primary();
            $table->string('status');
            $table->string('prioritaet')->nullable(); // Nullable, falls Priorität nicht gesetzt ist
            $table->string('startort_id', 50);
            $table->string('zielort_id', 50);
            $table->foreign('startort_id')->references('lagerplatz_id')->on('lagerplaetze');
            $table->foreign('zielort_id')->references('lagerplatz_id')->on('lagerplaetze');
            $table->string('fahrzeug_id')->nullable(); // Nullable, falls kein Fahrzeug zugewiesen ist
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auftraege');
    }
};
