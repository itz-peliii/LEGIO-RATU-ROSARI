<?php

namespace Database\Seeders;

use App\Models\Legio;
use Illuminate\Database\Seeder;

class LegioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Legio::firstOrCreate(
            ['name' => 'Legio Maria Presidium “Ratu Rosari”'],
            [
                'description' => '<p>Legio Maria Presidium “Ratu Rosari” Paroki Santo Petrus Tuban merupakan komunitas nirlaba kegerejaan yang telah berdiri sejak 9 tahun yang lalu. Komunitas ini terdiri dari jemaat Gereja Santo Petrus Tuban yang memiliki peran aktif dalam pelayanan umat.</p><p>Kegiatan rutin komunitas ini meliputi pertemuan mingguan, kunjungan perawatan kepada umat yang sakit, kunjungan serta doa penghiburan bagi keluarga yang berduka, hingga pengelolaan kas sukarela bulanan untuk bantuan sosial dan operasional.</p>',
                'sort_order'  => 1,
            ]
        );
    }
}
