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
        Schema::create('tbl_pastor_notes', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_note');
            //$table->string('judul_note')->nullable();
            //$table->string('ayat')->nullable();
            $table->text('note');
            $table->string('filename')->nullable();
            $table->string('path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pastor_notes');
    }
};