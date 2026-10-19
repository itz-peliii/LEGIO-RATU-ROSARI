<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Contoh kegiatan (2 sudah lewat, 3 akan datang) supaya halaman anggota ada isinya.
     */
    public function run(): void
    {
        // Tanggal contoh dihitung dari "hari ini", jadi jangan di-seed ulang kalau sudah ada data.
        if (Kegiatan::exists()) {
            return;
        }

        $items = [
            ['Pertemuan Mingguan Legio', 'Pertemuan rutin mingguan: doa Tessera, rapat, dan laporan kegiatan.', 'Balai Paroki Santo Petrus Tuban', now()->subDays(14)->setTime(18, 30)],
            ['Kunjungan Umat Sakit', 'Kunjungan dan doa bagi umat yang sedang sakit di wilayah paroki.', 'Wilayah Sidomulyo', now()->subDays(7)->setTime(9, 0)],
            ['Pertemuan Mingguan Legio', 'Pertemuan rutin mingguan: doa Tessera, rapat, dan laporan kegiatan.', 'Balai Paroki Santo Petrus Tuban', now()->addDays(3)->setTime(18, 30)],
            ['Doa Rosario Bersama', 'Doa Rosario bersama di Goa Maria untuk intensi paroki.', 'Goa Maria Santo Petrus Tuban', now()->addDays(8)->setTime(17, 0)],
            ['Kunjungan Keluarga Berduka', 'Kunjungan dan doa penghiburan bagi keluarga yang berduka.', 'Sesuai penugasan', now()->addDays(15)->setTime(10, 0)],
        ];

        foreach ($items as [$title, $description, $location, $heldAt]) {
            Kegiatan::create([
                'title'       => $title,
                'description' => $description,
                'location'    => $location,
                'held_at'     => $heldAt,
            ]);
        }
    }
}
