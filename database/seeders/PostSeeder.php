<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return;
        }

        $categories = Category::all();
        if ($categories->isEmpty()) {
            return;
        }

        $samplePosts = [
            [
                'title' => 'Peluncuran Portal Layanan Publik Terpadu Berbasis Digital',
                'summary' => 'Instansi meluncurkan portal baru guna mempermudah akses layanan dan pengaduan bagi seluruh lapisan masyarakat.',
                'content' => '<p>Instansi resmi memperkenalkan portal layanan publik terpadu yang memadukan berbagai kanal informasi, pelaporan, dan pengaduan masyarakat dalam satu pintu.</p><p>Melalui inovasi ini, masyarakat dapat mengajukan pertanyaan, melacak status permohonan informasi, dan membaca rilis resmi instansi secara transparan dan akuntabel.</p>',
                'is_featured' => true,
            ],
            [
                'title' => 'Sosialisasi Program Transparansi dan Keterbukaan Informasi Publik',
                'summary' => 'Kegiatan sosialisasi diadakan untuk meningkatkan pemahaman hak masyarakat atas informasi publik yang berkualitas.',
                'content' => '<p>Dalam rangka memperingati Hari Keterbukaan Informasi, diselenggarakan forum diskusi bersama perwakilan masyarakat, komunitas, dan akademisi.</p><p>Keterbukaan informasi adalah wujud nyata komitmen instansi dalam menjalankan tata kelola pemerintahan yang bersih dan melayani.</p>',
                'is_featured' => false,
            ],
            [
                'title' => 'Jadwal Pemeliharaan Sistem Layanan Online Akhir Pekan Ini',
                'summary' => 'Pemberitahuan pemeliharaan server rutin guna meningkatkan kestabilan dan kecepatan akses sistem.',
                'content' => '<p>Diberitahukan kepada seluruh pengguna layanan, akan dilakukan pemeliharaan server berkala pada hari Sabtu pukul 22.00 WIB hingga Minggu pukul 04.00 WIB.</p><p>Selama proses pemeliharaan berlangsung, beberapa layanan mungkin mengalami kendala sementara.</p>',
                'is_featured' => false,
            ],
        ];

        foreach ($samplePosts as $index => $item) {
            $cat = $categories->get($index % $categories->count());

            Post::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'user_id' => $user->id,
                    'category_id' => $cat->id,
                    'title' => $item['title'],
                    'summary' => $item['summary'],
                    'content' => $item['content'],
                    'status' => PostStatus::Published,
                    'published_at' => now()->subDays($index * 2),
                    'view_count' => rand(50, 200),
                    'is_featured' => $item['is_featured'],
                ]
            );
        }
    }
}
