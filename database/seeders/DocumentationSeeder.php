<?php

namespace Database\Seeders;

use App\Models\Documentation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DocumentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan folder target di storage/app/public/documentations sudah ada
        Storage::disk('public')->makeDirectory('documentations');

        // Daftar foto default beserta judul/caption-nya
        $defaultPhotos = [
            [
                'source_file' => public_path('assets/images/hero.jpg'),
                'target_name' => 'documentations/hero_default.jpg',
                'title'       => 'Misa Paroki & Perayaan Ekaristi',
            ],
            [
                'source_file' => public_path('assets/images/profil/gereja-depan.png'),
                'target_name' => 'documentations/gereja_depan.png',
                'title'       => 'Gereja Katolik Santo Petrus Tuban',
            ],
            [
                'source_file' => public_path('assets/images/profil/goa-maria.png'),
                'target_name' => 'documentations/goa_maria.png',
                'title'       => 'Goa Maria Santo Petrus Tuban',
            ],
            [
                'source_file' => public_path('assets/images/profil/balai-paroki.png'),
                'target_name' => 'documentations/balai_paroki.png',
                'title'       => 'Kegiatan Umat di Balai Paroki',
            ],
        ];

        foreach ($defaultPhotos as $index => $photo) {
            $imagePath = $photo['target_name'];

            // Jika file sumber ada di public/assets, salin file tersebut ke storage/app/public/documentations
            if (File::exists($photo['source_file'])) {
                Storage::disk('public')->put(
                    $imagePath,
                    File::get($photo['source_file'])
                );
            }

            // Simpan atau update record di database
            Documentation::updateOrCreate(
                ['image_path' => $imagePath],
                [
                    'title'      => $photo['title'],
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}