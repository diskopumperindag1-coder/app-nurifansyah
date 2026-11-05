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
                'name' => [
                    'id' => 'Layanan Administrasi',
                    'en' => 'Administrative Services',
                ],
                'description' => [
                    'id' => 'Pertanyaan seputar surat izin, rekomendasi, dan berkas administrasi.',
                    'en' => 'Inquiries regarding permits, recommendations, and administrative files.',
                ],
                'icon' => 'document-check',
                'sort_order' => 1,
            ],
            [
                'name' => [
                    'id' => 'Pengaduan & Aspirasi',
                    'en' => 'Complaints & Aspirations',
                ],
                'description' => [
                    'id' => 'Laporan kendala di lapangan, aspirasi perbaikan, atau saran pelayanan.',
                    'en' => 'Field issue reports, improvement aspirations, or service suggestions.',
                ],
                'icon' => 'chat-bubble-bottom-center-text',
                'sort_order' => 2,
            ],
            [
                'name' => [
                    'id' => 'Informasi Publik & Dokumen',
                    'en' => 'Public Information & Documents',
                ],
                'description' => [
                    'id' => 'Permohonan informasi berkala, dokumen resmi, dan statistik instansi.',
                    'en' => 'Periodic information requests, official documents, and institutional statistics.',
                ],
                'icon' => 'information-circle',
                'sort_order' => 3,
            ],
            [
                'name' => [
                    'id' => 'Layanan Kepegawaian & Magang',
                    'en' => 'Staffing & Internship Services',
                ],
                'description' => [
                    'id' => 'Informasi program magang, PKL, dan rekrutmen pegawai.',
                    'en' => 'Information on internship programs and employee recruitment.',
                ],
                'icon' => 'academic-cap',
                'sort_order' => 4,
            ],
        ];

        foreach ($topics as $topic) {
            QuestionTopic::updateOrCreate(
                ['slug' => Str::slug($topic['name']['id'])],
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
