<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbDetail extends Model
{
    protected $table = 'ppdb_details';

    protected $fillable = [
        'tahun',
        'kategori',
        'sub_kategori',
        'jumlah',
    ];
}
