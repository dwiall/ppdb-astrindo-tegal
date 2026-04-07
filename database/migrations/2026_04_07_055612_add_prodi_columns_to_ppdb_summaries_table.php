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
        Schema::table('ppdb_summaries', function (Blueprint $table) {
            $table->integer('total_akl')->default(0)->after('total_siswa');
            $table->integer('total_dkv')->default(0)->after('total_akl');
            $table->integer('total_tkj')->default(0)->after('total_dkv');
            $table->integer('total_to')->default(0)->after('total_tkj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_summaries', function (Blueprint $table) {
            $table->dropColumn([
                'total_akl',
                'total_dkv',
                'total_tkj',
                'total_to',
            ]);
        });
    }
};
