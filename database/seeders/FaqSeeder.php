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
                'question' => [
                    'id' => 'Berapa lama proses penerbitan surat rekomendasi?',
                    'en' => 'How long does the recommendation letter issuance process take?',
                ],
                'answer' => [
                    'id' => 'Proses verifikasi dan penerbitan berkas membutuhkan waktu 1–3 hari kerja sejak dokumen persyaratan dinyatakan lengkap.',
                    'en' => 'The verification and document issuance process takes 1–3 business days once all required documents are complete.',
                ],
                'sort_order' => 1,
            ],
            [
                'topic_id' => $adminTopic?->id,
                'question' => [
                    'id' => 'Apakah pengurusan layanan dikenakan biaya?',
                    'en' => 'Are there any fees for service requests?',
                ],
                'answer' => [
                    'id' => 'Seluruh layanan administrasi dan permohonan informasi publik di instansi kami adalah 100% GRATIS tanpa pungutan biaya.',
                    'en' => 'All administrative services and public information requests at our institution are 100% FREE of charge.',
                ],
                'sort_order' => 2,
            ],
            [
                'topic_id' => $infoTopic?->id,
                'question' => [
                    'id' => 'Bagaimana cara mengajukan pertanyaan atau permohonan informasi secara online?',
                    'en' => 'How do I submit an inquiry or information request online?',
                ],
                'answer' => [
                    'id' => 'Anda dapat mengisi formulir pengajuan pertanyaan pada tab "Tanya Kami" atau aplikasi mobile. Tim kami akan menindaklanjuti dan mengirimkan jawaban ke email Anda.',
                    'en' => 'You can fill out the inquiry form under the "Ask Us" tab or mobile app. Our team will follow up and send an answer to your email.',
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($faqs as $item) {
            Faq::updateOrCreate(
                ['sort_order' => $item['sort_order']],
                [
                    'topic_id' => $item['topic_id'],
                    'question' => $item['question'],
                    'answer' => $item['answer'],
                    'is_active' => true,
                ]
            );
        }
    }
}
