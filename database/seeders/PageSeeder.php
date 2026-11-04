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
                'title' => 'Tentang Kami',
                'content' => '<p>Instansi kami bertugas memberikan pelayanan publik terbaik, transparan, dan berkeadilan bagi seluruh lapisan masyarakat.</p><p>Didukung oleh tenaga profesional dan infrastruktur digital modern, kami berkomitmen untuk terus meningkatkan mutu pelayanan secara berkelanjutan.</p>',
                'sort_order' => 1,
            ],
            [
                'title' => 'Visi & Misi',
                'content' => '<h3>Visi</h3><p>Menjadi instansi terdepan dalam pelayanan publik yang modern, akuntabel, dan terpercaya.</p><h3>Misi</h3><ul><li>Mewujudkan pelayanan prima yang responsif terhadap aspirasi masyarakat.</li><li>Mengembangkan tata kelola berbasis teknologi informasi dan keterbukaan data.</li><li>Membangun sumber daya aparatur yang profesional dan berintegritas tinggi.</li></ul>',
                'sort_order' => 2,
            ],
            [
                'title' => 'Struktur Organisasi',
                'content' => '<p>Struktur kepemimpinan dan pembagian divisi operasional di lingkungan instansi mengacu pada peraturan perundang-undangan yang berlaku.</p><p>Setiap unit kerja memiliki fungsi yang saling terintegrasi dalam melayani kebutuhan masyarakat.</p>',
                'sort_order' => 3,
            ],
            [
                'title' => 'Maklumat Pelayanan',
                'content' => '<blockquote>"Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku."</blockquote>',
                'sort_order' => 4,
            ],
        ];

        foreach ($pages as $item) {
            Page::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
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
