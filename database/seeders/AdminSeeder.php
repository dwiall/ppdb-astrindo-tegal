<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================
        // KONFIGURASI ADMIN DEFAULT
        // =========================
        $adminName     = 'Admin PPDB';
        $adminEmail    = 'admin@ppdb.sch.id';   // GANTI JIKA PERLU
        $adminPassword = 'admin12345';          // GANTI JIKA PERLU

        // =========================
        // CEK APAKAH ADMIN SUDAH ADA
        // =========================
        $admin = User::where('email', $adminEmail)->first();

        if ($admin) {
            // Jika admin sudah ada → UPDATE PASSWORD (AMAN & TERKONTROL)
            $admin->update([
                'name'     => $adminName,
                'password' => Hash::make($adminPassword),
            ]);
        } else {
            // Jika admin belum ada → BUAT BARU
            User::create([
                'name'     => $adminName,
                'email'    => $adminEmail,
                'password' => Hash::make($adminPassword),
            ]);
        }
    }
}
