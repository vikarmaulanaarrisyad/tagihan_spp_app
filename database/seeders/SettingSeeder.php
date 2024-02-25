<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            [
                'email_sekolah' => 'support@w2charity.com'
            ],
            [
                'email_sekolah' => 'support@w2charity.com',
                'telpon_sekolah' => '081232323221',
                'nama_sekolah' => 'W2 Charity',
                'alamat_sekolah' => '-',
                'logo_sekolah' => 'logo_sekolah.png',
            ]
        );
    }
}
