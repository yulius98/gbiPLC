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
        Schema::create('tbl_youth_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul foto/video
            $table->text('description')->nullable(); // Deskripsi
            $table->enum('type', ['image', 'video'])->default('image'); // Tipe media
            $table->string('file_path'); // Path file di storage
            $table->string('thumbnail_path')->nullable(); // Path thumbnail untuk video
            $table->string('category')->nullable(); // Kategori: Youth Camp, Worship, dll
            $table->date('event_date')->nullable(); // Tanggal event
            $table->boolean('is_featured')->default(false); // Highlight foto
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_youth_galleries');
    }
};
