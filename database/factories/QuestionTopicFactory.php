<?php

namespace Database\Factories;

use App\Models\QuestionTopic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QuestionTopic>
 */
class QuestionTopicFactory extends Factory
{
    protected $model = QuestionTopic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nameId = fake('id_ID')->unique()->words(2, true);
        $nameEn = fake('en_US')->unique()->words(2, true);

        return [
            'name' => [
                'id' => ucfirst($nameId),
                'en' => ucfirst($nameEn),
            ],
            'slug' => Str::slug($nameId),
            'description' => [
                'id' => fake('id_ID')->sentence(),
                'en' => fake('en_US')->sentence(),
            ],
            'icon' => 'information-circle',
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
