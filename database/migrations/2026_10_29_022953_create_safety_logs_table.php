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
        Schema::create('safety_logs', function (Blueprint $table) {
        $table->id();
        $table->float('gas_ppm')->comment('Kadar gas dari MQ-2');
        $table->float('temperature')->comment('Suhu dalam Celcius dari DHT22');
        $table->float('humidity')->comment('Kelembaban udara % dari DHT22');
        $table->boolean('gas_danger')->default(false)->comment('Status bahaya gas');
        $table->string('buzzer_state', 10)->default('OFF');
        $table->string('relay_state', 10)->default('OFF');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_logs');
    }
};
