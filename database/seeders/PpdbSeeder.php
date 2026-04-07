<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ppdb;

class PpdbSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // 2021
            [
                'tahun' => 2021,
                'jumlah_pendaftar' => 210,
                'jumlah_diterima' => 180,
                'jumlah_registrasi' => 165,
                'asal_sekolah' => 'SMPN 1 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2021,
                'jumlah_pendaftar' => 160,
                'jumlah_diterima' => 135,
                'jumlah_registrasi' => 120,
                'asal_sekolah' => 'SMPN 2 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2021,
                'jumlah_pendaftar' => 110,
                'jumlah_diterima' => 95,
                'jumlah_registrasi' => 85,
                'asal_sekolah' => 'MTs Al-Hikmah',
                'asal_daerah' => 'Brebes',
            ],

            // 2022
            [
                'tahun' => 2022,
                'jumlah_pendaftar' => 230,
                'jumlah_diterima' => 200,
                'jumlah_registrasi' => 185,
                'asal_sekolah' => 'SMPN 1 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2022,
                'jumlah_pendaftar' => 175,
                'jumlah_diterima' => 150,
                'jumlah_registrasi' => 135,
                'asal_sekolah' => 'SMPN 2 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2022,
                'jumlah_pendaftar' => 130,
                'jumlah_diterima' => 110,
                'jumlah_registrasi' => 100,
                'asal_sekolah' => 'MTs Al-Hikmah',
                'asal_daerah' => 'Brebes',
            ],

            // 2023
            [
                'tahun' => 2023,
                'jumlah_pendaftar' => 200,
                'jumlah_diterima' => 170,
                'jumlah_registrasi' => 155,
                'asal_sekolah' => 'SMPN 1 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2023,
                'jumlah_pendaftar' => 150,
                'jumlah_diterima' => 125,
                'jumlah_registrasi' => 110,
                'asal_sekolah' => 'SMPN 2 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2023,
                'jumlah_pendaftar' => 140,
                'jumlah_diterima' => 120,
                'jumlah_registrasi' => 105,
                'asal_sekolah' => 'SMPN 3 Slawi',
                'asal_daerah' => 'Kabupaten Tegal',
            ],

            // 2024
            [
                'tahun' => 2024,
                'jumlah_pendaftar' => 215,
                'jumlah_diterima' => 185,
                'jumlah_registrasi' => 170,
                'asal_sekolah' => 'SMPN 1 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2024,
                'jumlah_pendaftar' => 165,
                'jumlah_diterima' => 140,
                'jumlah_registrasi' => 125,
                'asal_sekolah' => 'SMPN 2 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2024,
                'jumlah_pendaftar' => 155,
                'jumlah_diterima' => 130,
                'jumlah_registrasi' => 120,
                'asal_sekolah' => 'SMPN 3 Slawi',
                'asal_daerah' => 'Kabupaten Tegal',
            ],

            // 2025
            [
                'tahun' => 2025,
                'jumlah_pendaftar' => 245,
                'jumlah_diterima' => 215,
                'jumlah_registrasi' => 200,
                'asal_sekolah' => 'SMPN 1 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2025,
                'jumlah_pendaftar' => 190,
                'jumlah_diterima' => 165,
                'jumlah_registrasi' => 150,
                'asal_sekolah' => 'SMPN 2 Tegal',
                'asal_daerah' => 'Kota Tegal',
            ],
            [
                'tahun' => 2025,
                'jumlah_pendaftar' => 170,
                'jumlah_diterima' => 145,
                'jumlah_registrasi' => 135,
                'asal_sekolah' => 'SMPN 3 Slawi',
                'asal_daerah' => 'Kabupaten Tegal',
            ],
        ];

        foreach ($data as $row) {
            Ppdb::create($row);
        }
    }
}
