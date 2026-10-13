<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'hero_title' => 'Paroki Santo Petrus Tuban',
            'hero_subtitle' => '<p>Tempat pertumbuhan rohani dan pelayanan kasih bagi sesama.</p>',
            
            'about_us' => '<p>Gereja Katolik Paroki Santo Petrus Tuban merupakan bagian dari Keuskupan Surabaya yang berdedikasi menjadi wadah pertumbuhan rohani, persaudaraan, dan pelayanan kasih bagi seluruh umat Katolik di wilayah Tuban dan sekitarnya.</p><p>Melalui berbagai kegiatan liturgi, pembinaan iman, serta pelayanan sosial komunitas seperti Legio Maria Presidium "Ratu Rosari", kami terus berkomitmen menyebarkan nilai-nilai Injil dan menghadirkan kepedulian nyata bagi sesama.</p>',
            
            'visi_misi' => '<h3>Visi</h3><p>Menjadi wadah pelayanan awam Katolik yang setia, berbelaskasih, dan tangguh dalam mengamalkan kasih Kristus, meneladani kerendahan hati dan kesetiaan Bunda Maria di Paroki Santo Petrus Tuban.</p><h3>Misi</h3><ul><li>Memperdalam spiritualitas anggota melalui doa bersama dan perjumpaan rutin mingguan.</li><li>Melaksanakan perlawatan kasih kepada umat Katolik yang sakit, lansia, dan membutuhkan penguatan rohani.</li><li>Mewujudkan tata kelola komunitas yang tertib, transparan, dan terkoordinasi secara digital.</li></ul>',
            
            'schedule_note' => 'Misa di Gereja Santo Petrus, Tuban. Hari ini ditandai otomatis pada daftar.',
            
            'address' => 'Jl. Gereja No. 123, Tuban',
            'email' => 'info@parokituban.org',
            'phone' => '+62 812-3456-7890',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}