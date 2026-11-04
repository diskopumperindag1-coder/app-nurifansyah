<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\QuestionTopic;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminTopic = QuestionTopic::where('slug', 'layanan-administrasi')->first();
        $infoTopic = QuestionTopic::where('slug', 'informasi-publik-dokumen')->first();

        $faqs = [
            [
                'topic_id' => $adminTopic?->id,
                'question' => 'Berapa lama proses penerbitan surat rekomendasi?',
                'answer' => 'Proses verifikasi dan penerbitan berkas membutuhkan waktu 1–3 hari kerja sejak dokumen persyaratan dinyatakan lengkap.',
                'sort_order' => 1,
            ],
            [
                'topic_id' => $adminTopic?->id,
                'question' => 'Apakah pengurusan layanan dikenakan biaya?',
                'answer' => 'Seluruh layanan administrasi dan permohonan informasi publik di instansi kami adalah 100% GRATIS tanpa pungutan biaya.',
                'sort_order' => 2,
            ],
            [
                'topic_id' => $infoTopic?->id,
                'question' => 'Bagaimana cara mengajukan pertanyaan atau permohonan informasi secara online?',
                'answer' => 'Anda dapat mengisi formulir pengajuan pertanyaan pada tab "Tanya Kami" atau aplikasi mobile. Tim kami akan menindaklanjuti dan mengirimkan jawaban ke email Anda.',
                'sort_order' => 3,
            ],
        ];

        foreach ($faqs as $item) {
            Faq::updateOrCreate(
                ['question' => $item['question']],
                [
                    'topic_id' => $item['topic_id'],
                    'answer' => $item['answer'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
