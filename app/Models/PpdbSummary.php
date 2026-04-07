<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbSummary extends Model
{
    protected $table = 'ppdb_summaries';

    protected $fillable = [
        'tahun',
        'total_siswa',
        'total_akl',
        'total_dkv',
        'total_tkj',
        'total_to',
    ];
}
