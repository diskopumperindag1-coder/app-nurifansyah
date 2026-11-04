<?php

namespace Database\Seeders;

use App\Models\QuestionTopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Layanan Administrasi',
                'description' => 'Pertanyaan seputar surat izin, rekomendasi, dan berkas administrasi.',
                'icon' => 'document-check',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pengaduan & Aspirasi',
                'description' => 'Laporan kendala di lapangan, aspirasi perbaikan, atau saran pelayanan.',
                'icon' => 'chat-bubble-bottom-center-text',
                'sort_order' => 2,
            ],
            [
                'name' => 'Informasi Publik & Dokumen',
                'description' => 'Permohonan informasi berkala, dokumen resmi, dan statistik instansi.',
                'icon' => 'information-circle',
                'sort_order' => 3,
            ],
            [
                'name' => 'Layanan Kepegawaian & Magang',
                'description' => 'Informasi program magang, PKL, dan rekrutmen pegawai.',
                'icon' => 'academic-cap',
                'sort_order' => 4,
            ],
        ];

        foreach ($topics as $topic) {
            QuestionTopic::updateOrCreate(
                ['slug' => Str::slug($topic['name'])],
                [
                    'name' => $topic['name'],
                    'description' => $topic['description'],
                    'icon' => $topic['icon'],
                    'sort_order' => $topic['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
