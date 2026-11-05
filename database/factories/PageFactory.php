<?php

namespace Database\Factories;

use App\Enums\PageStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titleId = fake('id_ID')->sentence(3);
        $titleEn = fake('en_US')->sentence(3);

        return [
            'user_id' => User::factory(),
            'title' => [
                'id' => $titleId,
                'en' => $titleEn,
            ],
            'slug' => Str::slug($titleId),
            'content' => [
                'id' => fake('id_ID')->paragraphs(3, true),
                'en' => fake('en_US')->paragraphs(3, true),
            ],
            'image' => null,
            'status' => PageStatus::Published,
            'sort_order' => fake()->numberBetween(0, 10),
            'show_in_menu' => true,
        ];
    }
}
