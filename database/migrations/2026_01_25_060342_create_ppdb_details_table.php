<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_details', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('kategori');       // wilayah / sekolah / jurusan
            $table->string('sub_kategori');   // nama kota, sekolah, jurusan
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_details');
    }
};
