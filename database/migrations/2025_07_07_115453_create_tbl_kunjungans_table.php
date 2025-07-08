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
        Schema::create('tbl_kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jemaat')->constrained('users')->onDelete('cascade');
            $table->date('tglkunjungan');
            $table->text('nama_timbesuk');
            $table->string('filename')->nullable();
            $table->string('path')->nullable();
            $table->text('keterangan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_kunjungans');
    }
};