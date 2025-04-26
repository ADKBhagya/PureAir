<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('triggered_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('sensor_id');        // From sensors table
            $table->string('pollutant_type');    // AQI, PM2.5, etc
            $table->integer('threshold');        // Example: 100
            $table->integer('aqi_value');        // The AQI value that triggered the alert
            $table->string('status')->default('unread'); // unread / read
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triggered_alerts');
    }
};
