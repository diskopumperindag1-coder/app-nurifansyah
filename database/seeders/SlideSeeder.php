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
                'title' => [
                    'id' => 'Selamat Datang di Portal Resmi Instansi',
                    'en' => 'Welcome to the Official Institutional Portal',
                ],
                'description' => [
                    'id' => 'Akses informasi cepat, transparan, dan terpercaya langsung dalam genggaman Anda.',
                    'en' => 'Fast, transparent, and reliable information access right in your hands.',
                ],
                'image' => 'slides/banner1.jpg',
                'link_url' => '/posts',
                'sort_order' => 1,
            ],
            [
                'title' => [
                    'id' => 'Layanan Tanya Masyarakat Terbuka 24/7',
                    'en' => 'Public Inquiry Service Open 24/7',
                ],
                'description' => [
                    'id' => 'Sampaikan pertanyaan dan aspirasi Anda secara langsung melalui formulir pertanyaan online.',
                    'en' => 'Submit your questions and aspirations directly via our online inquiry form.',
                ],
                'image' => 'slides/banner2.jpg',
                'link_url' => '/questions',
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $item) {
            Slide::updateOrCreate(
                ['sort_order' => $item['sort_order']],
                [
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'link_url' => $item['link_url'],
                    'is_active' => true,
                    'start_date' => now()->toDateString(),
                ]
            );
        }
    }
}
