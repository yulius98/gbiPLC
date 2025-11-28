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
        Schema::create('tbl_youth_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul kegiatan
            $table->text('description'); // Deskripsi kegiatan
            $table->enum('type', ['weekly', 'special_event'])->default('weekly'); // Tipe: Rutin/Event
            $table->string('day_of_week')->nullable(); // Hari: Minggu, Senin, dll (untuk weekly)
            $table->date('event_date')->nullable(); // Tanggal spesifik (untuk special event)
            $table->time('start_time'); // Waktu mulai
            $table->time('end_time'); // Waktu selesai
            $table->string('location'); // Lokasi
            $table->string('location_url')->nullable(); // Google Maps URL
            $table->string('category')->nullable(); // Kategori: Ibadah, Small Group, dll
            $table->boolean('is_active')->default(true); // Status aktif
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_youth_schedules');
    }
};
