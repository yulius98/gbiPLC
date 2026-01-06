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
        Schema::create('reading_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('day'); // 1-365
            $table->string('morning_passage'); // "GEN.1-GEN.2"
            $table->string('evening_passage'); // "GEN.3-GEN.4"
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_schedules');
    }
};
