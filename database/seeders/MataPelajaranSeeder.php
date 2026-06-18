<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapel = [
            'TK A' => ['Mengenal Huruf', 'Mewarnai'],
            'TK B' => ['Membaca Dasar', 'Berhitung'],
        ];

        foreach ($mapel as $kelasNama => $daftarMapel) {
            $kelas = Kelas::where('nama_kelas', $kelasNama)->first();
            if (!$kelas) {
                continue;
            }

            foreach ($daftarMapel as $namaMapel) {
                MataPelajaran::firstOrCreate([
                    'nama_mapel' => $namaMapel,
                    'kelas_id' => $kelas->id,
                ]);
            }
        }
    }
}
