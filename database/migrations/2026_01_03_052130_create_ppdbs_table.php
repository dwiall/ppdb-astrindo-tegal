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
        Schema::create('ppdbs', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('kategori');
            $table->string('sub_kategori');
            $table->integer('jumlah');
            $table->timestamps();
            
            $table->unique(['tahun', 'kategori', 'sub_kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdbs');
    }
};
