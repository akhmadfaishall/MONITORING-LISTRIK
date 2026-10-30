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
        Schema::create('device_states', function (Blueprint $table) {
        $table->id();
        $table->string('device_name')->unique(); // misal: 'relay_led', 'buzzer'
        $table->string('state')->default('OFF');  // 'ON' atau 'OFF'
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_states');
    }
};
