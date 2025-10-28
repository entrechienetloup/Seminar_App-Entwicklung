<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meldungen', function (Blueprint $table) {
            $table->id();

            // Match fahrzeuge.fahrzeug_id (STRING) as foreign key
            $table->string('fahrzeug_id');
            $table->foreign('fahrzeug_id')->references('fahrzeug_id')->on('fahrzeuge')->onDelete('cascade');

            $table->string('typ');
            $table->text('beschreibung')->nullable();
            $table->timestamp('gemeldet_am')->useCurrent();
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meldungen');
    }
};
