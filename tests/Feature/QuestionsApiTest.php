<?php

namespace Tests\Feature;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\Faq;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_question_topics(): void
    {
        QuestionTopic::factory()->count(2)->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/question-topics');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_list_faqs(): void
    {
        $topic = QuestionTopic::factory()->create();
        Faq::factory()->create([
            'topic_id' => $topic->id,
            'is_active' => true,
        ]);
        Faq::factory()->create([
            'topic_id' => $topic->id,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/v1/faqs');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_list_only_answered_questions(): void
    {
        $topic = QuestionTopic::factory()->create();
        $user = User::factory()->create();

        $qAnswered = Question::factory()->create([
            'topic_id' => $topic->id,
            'status' => QuestionStatus::Answered,
        ]);

        QuestionAnswer::create([
            'question_id' => $qAnswered->id,
            'user_id' => $user->id,
            'body' => 'Jawaban resmi',
            'status' => AnswerStatus::Published,
            'is_published' => true,
            'answered_at' => now(),
        ]);

        Question::factory()->create([
            'topic_id' => $topic->id,
            'status' => QuestionStatus::Pending,
        ]);

        $response = $this->getJson('/api/v1/questions');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $qAnswered->id);
    }

    public function test_citizen_can_submit_question(): void
    {
        $topic = QuestionTopic::factory()->create();

        $payload = [
            'topic_id' => $topic->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081299998888',
            'subject' => 'Pertanyaan mengenai jadwal pelayanan',
            'body' => 'Mohon info mengenai jam operasional pelayanan pada hari libur nasional.',
        ];

        $response = $this->postJson('/api/v1/questions', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.subject', $payload['subject']);

        $this->assertDatabaseHas('questions', [
            'email' => 'budi@example.com',
            'status' => QuestionStatus::Pending->value,
        ]);
    }

    public function test_question_submission_requires_valid_data(): void
    {
        $response = $this->postJson('/api/v1/questions', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topic_id', 'name', 'email', 'subject', 'body']);
    }
}
