<?php

namespace Database\Factories;

use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slide>
 */
class SlideFactory extends Factory
{
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => [
                'id' => fake('id_ID')->sentence(4),
                'en' => fake('en_US')->sentence(4),
            ],
            'description' => [
                'id' => fake('id_ID')->sentence(8),
                'en' => fake('en_US')->sentence(8),
            ],
            'image' => 'slides/sample.jpg',
            'link_url' => fake()->url(),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
        ];
    }
}
