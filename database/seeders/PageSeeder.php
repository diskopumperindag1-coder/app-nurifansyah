<?php

namespace Database\Seeders;

use App\Enums\PageStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $pages = [
            [
                'title' => [
                    'id' => 'Tentang Kami',
                    'en' => 'About Us',
                ],
                'content' => [
                    'id' => '<p>Instansi kami bertugas memberikan pelayanan publik terbaik, transparan, dan berkeadilan bagi seluruh lapisan masyarakat.</p><p>Didukung oleh tenaga profesional dan infrastruktur digital modern, kami berkomitmen untuk terus meningkatkan mutu pelayanan secara berkelanjutan.</p>',
                    'en' => '<p>Our institution is tasked with providing the best, transparent, and equitable public services for all levels of society.</p><p>Supported by professional staff and modern digital infrastructure, we are committed to continuously improving service quality.</p>',
                ],
                'sort_order' => 1,
            ],
            [
                'title' => [
                    'id' => 'Visi & Misi',
                    'en' => 'Vision & Mission',
                ],
                'content' => [
                    'id' => '<h3>Visi</h3><p>Menjadi instansi terdepan dalam pelayanan publik yang modern, akuntabel, dan terpercaya.</p><h3>Misi</h3><ul><li>Mewujudkan pelayanan prima yang responsif terhadap aspirasi masyarakat.</li><li>Mengembangkan tata kelola berbasis teknologi informasi dan keterbukaan data.</li><li>Membangun sumber daya aparatur yang profesional dan berintegritas tinggi.</li></ul>',
                    'en' => '<h3>Vision</h3><p>To be a leading institution in modern, accountable, and trusted public services.</p><h3>Mission</h3><ul><li>Realize prime service that is responsive to public aspirations.</li><li>Develop governance based on information technology and open data.</li><li>Build professional and high-integrity apparatus human resources.</li></ul>',
                ],
                'sort_order' => 2,
            ],
            [
                'title' => [
                    'id' => 'Struktur Organisasi',
                    'en' => 'Organizational Structure',
                ],
                'content' => [
                    'id' => '<p>Struktur kepemimpinan dan pembagian divisi operasional di lingkungan instansi mengacu pada peraturan perundang-undangan yang berlaku.</p><p>Setiap unit kerja memiliki fungsi yang saling terintegrasi dalam melayani kebutuhan masyarakat.</p>',
                    'en' => '<p>The leadership structure and division of operational units within the institution refer to applicable laws and regulations.</p><p>Each work unit operates with integrated functions to serve public needs.</p>',
                ],
                'sort_order' => 3,
            ],
            [
                'title' => [
                    'id' => 'Maklumat Pelayanan',
                    'en' => 'Service Declaration',
                ],
                'content' => [
                    'id' => '<blockquote>"Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku."</blockquote>',
                    'en' => '<blockquote>"We hereby declare our commitment to providing services in accordance with established service standards and if we fail to keep this promise, we are prepared to accept sanctions in accordance with applicable laws."</blockquote>',
                ],
                'sort_order' => 4,
            ],
        ];

        foreach ($pages as $item) {
            Page::updateOrCreate(
                ['slug' => Str::slug($item['title']['id'])],
                [
                    'user_id' => $user?->id,
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'status' => PageStatus::Published,
                    'sort_order' => $item['sort_order'],
                    'show_in_menu' => true,
                ]
            );
        }
    }
}
