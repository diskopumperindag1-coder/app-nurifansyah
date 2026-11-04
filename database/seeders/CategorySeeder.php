<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Berita Utama',
                'description' => 'Informasi dan warta terkini seputar kegiatan instansi.',
                'icon' => 'newspaper',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pengumuman',
                'description' => 'Pemberitahuan resmi, edaran, dan pengumuman instansi.',
                'icon' => 'megaphone',
                'sort_order' => 2,
            ],
            [
                'name' => 'Artikel & Opini',
                'description' => 'Ulasan, edukasi publik, dan artikel informatif.',
                'icon' => 'document-text',
                'sort_order' => 3,
            ],
            [
                'name' => 'Agenda Kegiatan',
                'description' => 'Jadwal program kerja dan acara publik.',
                'icon' => 'calendar',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
