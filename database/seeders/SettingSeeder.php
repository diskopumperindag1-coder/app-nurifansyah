<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'Portal Resmi Instansi',
                'type' => 'text',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'Melayani Masyarakat dengan Integritas dan Transparansi',
                'type' => 'text',
            ],
            [
                'group' => 'general',
                'key' => 'site_description',
                'value' => 'Portal informasi resmi dan layanan pengajuan pertanyaan masyarakat.',
                'type' => 'textarea',
            ],
            [
                'group' => 'contact',
                'key' => 'office_address',
                'value' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'type' => 'textarea',
            ],
            [
                'group' => 'contact',
                'key' => 'office_phone',
                'value' => '(021) 1234567',
                'type' => 'text',
            ],
            [
                'group' => 'contact',
                'key' => 'office_email',
                'value' => 'kontak@instansi.go.id',
                'type' => 'text',
            ],
            [
                'group' => 'social',
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/instansiresmi',
                'type' => 'text',
            ],
            [
                'group' => 'social',
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/instansiresmi',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
