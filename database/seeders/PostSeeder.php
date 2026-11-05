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
                'title' => [
                    'id' => 'Peluncuran Portal Layanan Publik Terpadu Berbasis Digital',
                    'en' => 'Launch of Integrated Digital Public Service Portal',
                ],
                'summary' => [
                    'id' => 'Instansi meluncurkan portal baru guna mempermudah akses layanan dan pengaduan bagi seluruh lapisan masyarakat.',
                    'en' => 'The institution launches a new portal to facilitate access to services and public inquiries.',
                ],
                'content' => [
                    'id' => '<p>Instansi resmi memperkenalkan portal layanan publik terpadu yang memadukan berbagai kanal informasi, pelaporan, dan pengaduan masyarakat dalam satu pintu.</p><p>Melalui inovasi ini, masyarakat dapat mengajukan pertanyaan, melacak status permohonan informasi, dan membaca rilis resmi instansi secara transparan dan akuntabel.</p>',
                    'en' => '<p>The institution officially introduces an integrated public service portal combining multiple information channels, reporting, and public complaints under one roof.</p><p>Through this innovation, citizens can submit questions, track information request statuses, and read official press releases transparently and accountably.</p>',
                ],
                'is_featured' => true,
            ],
            [
                'title' => [
                    'id' => 'Sosialisasi Program Transparansi dan Keterbukaan Informasi Publik',
                    'en' => 'Dissemination of Transparency and Public Information Openness Program',
                ],
                'summary' => [
                    'id' => 'Kegiatan sosialisasi diadakan untuk meningkatkan pemahaman hak masyarakat atas informasi publik yang berkualitas.',
                    'en' => 'Public workshop held to enhance citizens understanding of their right to quality public information.',
                ],
                'content' => [
                    'id' => '<p>Dalam rangka memperingati Hari Keterbukaan Informasi, diselenggarakan forum diskusi bersama perwakilan masyarakat, komunitas, dan akademisi.</p><p>Keterbukaan informasi adalah wujud nyata komitmen instansi dalam menjalankan tata kelola pemerintahan yang bersih dan melayani.</p>',
                    'en' => '<p>In celebration of Information Freedom Day, a discussion forum was held with community representatives and academics.</p><p>Information openness is a tangible manifestation of our commitment to clean and serving governance.</p>',
                ],
                'is_featured' => false,
            ],
            [
                'title' => [
                    'id' => 'Jadwal Pemeliharaan Sistem Layanan Online Akhir Pekan Ini',
                    'en' => 'Scheduled System Maintenance This Weekend',
                ],
                'summary' => [
                    'id' => 'Pemberitahuan pemeliharaan server rutin guna meningkatkan kestabilan dan kecepatan akses sistem.',
                    'en' => 'Notice of routine server maintenance to improve system stability and access speed.',
                ],
                'content' => [
                    'id' => '<p>Diberitahukan kepada seluruh pengguna layanan, akan dilakukan pemeliharaan server berkala pada hari Sabtu pukul 22.00 WIB hingga Minggu pukul 04.00 WIB.</p><p>Selama proses pemeliharaan berlangsung, beberapa layanan mungkin mengalami kendala sementara.</p>',
                    'en' => '<p>Please be advised that scheduled maintenance will take place on Saturday from 10:00 PM to Sunday 04:00 AM.</p><p>During the maintenance window, some services may experience temporary interruptions.</p>',
                ],
                'is_featured' => false,
            ],
        ];

        foreach ($samplePosts as $index => $item) {
            $cat = $categories->get($index % $categories->count());

            Post::updateOrCreate(
                ['slug' => Str::slug($item['title']['id'])],
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
