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
        Schema::create('tbl_youth_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul program
            $table->text('description'); // Deskripsi program
            $table->string('icon')->nullable(); // Icon SVG atau class
            $table->string('frequency'); // Frekuensi: Mingguan, Bulanan, dll
            $table->string('category')->nullable(); // Kategori: Ibadah, Small Group, Camp, dll
            $table->boolean('is_active')->default(true); // Status aktif/non-aktif
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_youth_programs');
    }
};
