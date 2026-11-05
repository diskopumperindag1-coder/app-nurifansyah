<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titleId = fake('id_ID')->sentence(6);
        $titleEn = fake('en_US')->sentence(6);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => [
                'id' => $titleId,
                'en' => $titleEn,
            ],
            'slug' => Str::slug($titleId).'-'.fake()->unique()->numberBetween(100, 999),
            'summary' => [
                'id' => fake('id_ID')->paragraph(2),
                'en' => fake('en_US')->paragraph(2),
            ],
            'content' => [
                'id' => fake('id_ID')->paragraphs(4, true),
                'en' => fake('en_US')->paragraphs(4, true),
            ],
            'image' => null,
            'status' => PostStatus::Published,
            'published_at' => now(),
            'view_count' => fake()->numberBetween(10, 500),
            'is_featured' => fake()->boolean(20),
        ];
    }
}
