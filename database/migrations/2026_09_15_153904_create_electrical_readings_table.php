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
    Schema::create('electrical_readings', function (Blueprint $table) {
        $table->id();

        $table->string('device_id')->default('F3-ESP32-001');

        $table->decimal('voltage', 8, 2)->nullable();
        $table->decimal('current', 8, 3)->nullable();
        $table->decimal('power', 10, 2)->nullable();
        $table->decimal('energy', 12, 3)->nullable();
        $table->decimal('frequency', 6, 2)->nullable();
        $table->decimal('power_factor', 5, 3)->nullable();
        $table->decimal('apparent_power', 10, 2)->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electrical_readings');
    }
};
