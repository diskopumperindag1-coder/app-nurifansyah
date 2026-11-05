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
                'name' => [
                    'id' => 'Berita Utama',
                    'en' => 'Headline News',
                ],
                'description' => [
                    'id' => 'Informasi dan warta terkini seputar kegiatan instansi.',
                    'en' => 'Latest news and information regarding institutional activities.',
                ],
                'icon' => 'newspaper',
                'sort_order' => 1,
            ],
            [
                'name' => [
                    'id' => 'Pengumuman',
                    'en' => 'Announcements',
                ],
                'description' => [
                    'id' => 'Pemberitahuan resmi, edaran, dan pengumuman instansi.',
                    'en' => 'Official notices, circulars, and institutional announcements.',
                ],
                'icon' => 'megaphone',
                'sort_order' => 2,
            ],
            [
                'name' => [
                    'id' => 'Artikel & Opini',
                    'en' => 'Articles & Opinions',
                ],
                'description' => [
                    'id' => 'Ulasan, edukasi publik, dan artikel informatif.',
                    'en' => 'Reviews, public education, and informative articles.',
                ],
                'icon' => 'document-text',
                'sort_order' => 3,
            ],
            [
                'name' => [
                    'id' => 'Agenda Kegiatan',
                    'en' => 'Event Agenda',
                ],
                'description' => [
                    'id' => 'Jadwal program kerja dan acara publik.',
                    'en' => 'Schedule of work programs and public events.',
                ],
                'icon' => 'calendar',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name']['id'])],
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
