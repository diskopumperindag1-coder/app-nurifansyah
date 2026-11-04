<?php

namespace Database\Seeders;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $topic = QuestionTopic::first();

        if (! $topic || ! $user) {
            return;
        }

        $q1 = Question::updateOrCreate(
            ['email' => 'ahmad.fauzi@gmail.com', 'subject' => 'Persyaratan Legalisir Berkas Dokumen'],
            [
                'topic_id' => $topic->id,
                'name' => 'Ahmad Fauzi',
                'phone' => '081234567890',
                'body' => 'Selamat pagi, mohon informasi mengenai persyaratan legalisir dokumen secara tatap muka maupun online. Terima kasih.',
                'status' => QuestionStatus::Answered,
                'ip_address' => '127.0.0.1',
            ]
        );

        QuestionAnswer::updateOrCreate(
            ['question_id' => $q1->id],
            [
                'user_id' => $user->id,
                'body' => 'Selamat pagi Sdr. Ahmad. Untuk legalisir dokumen, silakan membawa dokumen asli beserta fotokopi rangkap 3 ke loket pelayanan pada jam kerja (08.00 - 15.00 WIB). Layanan ini tidak dipungut biaya.',
                'status' => AnswerStatus::Published,
                'is_published' => true,
                'answered_at' => now(),
            ]
        );

        Question::updateOrCreate(
            ['email' => 'siti.rahma@yahoo.com', 'subject' => 'Pengajuan Tempat Magang Mahasiswa Semester 7'],
            [
                'topic_id' => $topic->id,
                'name' => 'Siti Rahma',
                'phone' => '085711223344',
                'body' => 'Halo admin, apakah saat ini instansi masih membuka kuota penerimaan magang riset untuk mahasiswa perguruan tinggi?',
                'status' => QuestionStatus::Pending,
                'ip_address' => '127.0.0.1',
            ]
        );
    }
}
