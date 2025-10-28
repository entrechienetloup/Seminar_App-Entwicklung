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
Schema::create('route_routenpunkt', function (Blueprint $table) {
    $table->unsignedBigInteger('routen_id');
    $table->string('routenpunkte_id');
    $table->integer('reihenfolge');

    $table->foreign('routen_id')->references('id')->on('routen')->onDelete('cascade');
    $table->foreign('routenpunkte_id')->references('routenpunkte_id')->on('routenpunkte')->onDelete('cascade');

    $table->primary(['routen_id', 'routenpunkte_id']); // Composite key
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_routenpunkt');
    }
};
