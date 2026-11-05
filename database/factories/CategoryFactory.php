<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

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
            'icon' => 'heroicon-o-tag',
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
