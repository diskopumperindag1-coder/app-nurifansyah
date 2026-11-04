<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Selamat Datang di Portal Resmi Instansi',
                'description' => 'Akses informasi cepat, transparan, dan terpercaya langsung dalam genggaman Anda.',
                'image' => 'slides/banner1.jpg',
                'link_url' => '/posts',
                'sort_order' => 1,
            ],
            [
                'title' => 'Layanan Tanya Masyarakat Terbuka 24/7',
                'description' => 'Sampaikan pertanyaan dan aspirasi Anda secara langsung melalui formulir pertanyaan online.',
                'image' => 'slides/banner2.jpg',
                'link_url' => '/questions',
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $item) {
            Slide::updateOrCreate(
                ['title' => $item['title']],
                [
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'link_url' => $item['link_url'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                    'start_date' => now()->toDateString(),
                ]
            );
        }
    }
}
