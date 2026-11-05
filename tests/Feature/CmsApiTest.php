<?php

namespace Tests\Feature;

use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_published_posts(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => PostStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => PostStatus::Draft,
        ]);

        $response = $this->getJson('/api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'summary', 'published_at', 'category', 'author'],
                ],
                'links',
                'meta',
            ])
            ->assertJsonCount(1, 'data');
    }

    public function test_can_show_single_post_by_slug(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'slug' => 'test-article-slug',
            'status' => PostStatus::Published,
            'published_at' => now()->subDay(),
            'view_count' => 5,
        ]);

        $response = $this->getJson('/api/v1/posts/test-article-slug');

        $response->assertStatus(200)
            ->assertJsonPath('data.title', $post->title)
            ->assertJsonPath('data.slug', 'test-article-slug');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'view_count' => 6,
        ]);
    }

    public function test_can_show_page_by_slug(): void
    {
        $page = Page::factory()->create([
            'slug' => 'visi-misi',
            'status' => PageStatus::Published,
        ]);

        $response = $this->getJson('/api/v1/pages/visi-misi');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'visi-misi')
            ->assertJsonPath('data.title', $page->title);
    }

    public function test_can_list_categories(): void
    {
        Category::factory()->count(3)->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_list_active_slides(): void
    {
        Slide::factory()->create([
            'is_active' => true,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
        ]);

        Slide::factory()->create([
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/v1/slides');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_get_menu_by_location(): void
    {
        $menu = Menu::create([
            'name' => 'Header Menu',
            'location' => 'header',
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => 'Beranda',
            'url' => '/',
            'type' => 'custom',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/menus/header');

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Header Menu')
            ->assertJsonCount(1, 'data.items');
    }

    public function test_can_get_settings(): void
    {
        Setting::setValue('site_name', 'Portal Instansi', 'general', 'text');

        $response = $this->getJson('/api/v1/settings');

        $response->assertStatus(200)
            ->assertJsonPath('data.settings.site_name', 'Portal Instansi');
    }

    public function test_can_get_posts_with_multilingual_locale(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'name' => [
                'id' => 'Berita ID',
                'en' => 'News EN',
            ],
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => [
                'id' => 'Judul Bahasa Indonesia',
                'en' => 'English Headline Title',
            ],
            'summary' => [
                'id' => 'Ringkasan ID',
                'en' => 'Summary EN',
            ],
            'status' => PostStatus::Published,
            'published_at' => now()->subHour(),
        ]);

        // Default / ID
        $responseId = $this->getJson('/api/v1/posts', ['Accept-Language' => 'id']);
        $responseId->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Judul Bahasa Indonesia')
            ->assertJsonPath('data.0.category.name', 'Berita ID');

        // English via Accept-Language
        $responseEn = $this->getJson('/api/v1/posts', ['Accept-Language' => 'en']);
        $responseEn->assertStatus(200)
            ->assertJsonPath('data.0.title', 'English Headline Title')
            ->assertJsonPath('data.0.category.name', 'News EN');

        // English via query param ?locale=en
        $responseQuery = $this->getJson('/api/v1/posts?locale=en');
        $responseQuery->assertStatus(200)
            ->assertJsonPath('data.0.title', 'English Headline Title');
    }
}
