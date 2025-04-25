<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('simulation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('frequency');
            $table->integer('baseline');
            $table->enum('variation', ['random', 'increasing', 'fluctuating']);
            $table->boolean('is_running')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_settings');
    }
};
