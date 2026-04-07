<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Rename tabel lama ke nama yang dipakai aplikasi
        if (Schema::hasTable('ppdb_summary') && !Schema::hasTable('ppdb_summaries')) {
            Schema::rename('ppdb_summary', 'ppdb_summaries');
        }
    }

    public function down(): void
    {
        // Balikin kalau di-rollback
        if (Schema::hasTable('ppdb_summaries') && !Schema::hasTable('ppdb_summary')) {
            Schema::rename('ppdb_summaries', 'ppdb_summary');
        }
    }
};
