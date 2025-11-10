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
        Schema::create('tbl_ibadah_rayas', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_ibadah');
            $table->string('ibadah_ke')->nullable();
            $table->string('link_ibadah')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ibadah_rayas');
    }
};
