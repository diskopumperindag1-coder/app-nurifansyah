<?php

namespace Database\Factories;

use App\Models\Faq;
use App\Models\QuestionTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'topic_id' => QuestionTopic::factory(),
            'question' => [
                'id' => fake('id_ID')->sentence(6).'?',
                'en' => fake('en_US')->sentence(6).'?',
            ],
            'answer' => [
                'id' => fake('id_ID')->paragraph(2),
                'en' => fake('en_US')->paragraph(2),
            ],
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
