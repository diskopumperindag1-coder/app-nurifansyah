<?php

namespace Tests\Feature;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Slides\SlideResource;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultilingualModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_supports_multilingual_attributes(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => [
                'id' => 'Judul Berita Indonesia',
                'en' => 'English News Title',
            ],
            'slug' => 'judul-berita-indonesia',
            'summary' => [
                'id' => 'Ringkasan berita ID',
                'en' => 'News summary EN',
            ],
            'content' => [
                'id' => '<p>Konten ID</p>',
                'en' => '<p>Content EN</p>',
            ],
        ]);

        $this->assertSame('Judul Berita Indonesia', $post->getTranslation('title', 'id'));
        $this->assertSame('English News Title', $post->getTranslation('title', 'en'));

        app()->setLocale('id');
        $this->assertSame('Judul Berita Indonesia', $post->title);
        $this->assertSame('Ringkasan berita ID', $post->summary);

        app()->setLocale('en');
        $this->assertSame('English News Title', $post->title);
        $this->assertSame('News summary EN', $post->summary);
    }

    public function test_category_and_page_support_multilingual_attributes(): void
    {
        $category = Category::create([
            'name' => [
                'id' => 'Pelayanan',
                'en' => 'Services',
            ],
            'slug' => 'pelayanan',
            'description' => [
                'id' => 'Deskripsi layanan',
                'en' => 'Service description',
            ],
        ]);

        $this->assertSame('Pelayanan', $category->getTranslation('name', 'id'));
        $this->assertSame('Services', $category->getTranslation('name', 'en'));

        $page = Page::create([
            'title' => [
                'id' => 'Profil Kami',
                'en' => 'Our Profile',
            ],
            'slug' => 'profil-kami',
            'content' => [
                'id' => 'Isi profil ID',
                'en' => 'Profile content EN',
            ],
        ]);

        $this->assertSame('Profil Kami', $page->getTranslation('title', 'id'));
        $this->assertSame('Our Profile', $page->getTranslation('title', 'en'));
    }

    public function test_faq_and_slide_support_multilingual_attributes(): void
    {
        $faq = Faq::create([
            'question' => [
                'id' => 'Apakah ini gratis?',
                'en' => 'Is this free?',
            ],
            'answer' => [
                'id' => 'Ya, ini gratis.',
                'en' => 'Yes, this is free.',
            ],
        ]);

        $this->assertSame('Apakah ini gratis?', $faq->getTranslation('question', 'id'));
        $this->assertSame('Is this free?', $faq->getTranslation('question', 'en'));

        $slide = Slide::create([
            'title' => [
                'id' => 'Banner Utama',
                'en' => 'Main Banner',
            ],
            'image' => 'slides/banner.jpg',
        ]);

        $this->assertSame('Banner Utama', $slide->getTranslation('title', 'id'));
        $this->assertSame('Main Banner', $slide->getTranslation('title', 'en'));
    }

    public function test_filament_resources_define_translatable_attributes(): void
    {
        $this->assertContains('title', PostResource::getTranslatableAttributes());
        $this->assertContains('content', PostResource::getTranslatableAttributes());

        $this->assertContains('name', CategoryResource::getTranslatableAttributes());
        $this->assertContains('description', CategoryResource::getTranslatableAttributes());

        $this->assertContains('title', PageResource::getTranslatableAttributes());
        $this->assertContains('content', PageResource::getTranslatableAttributes());

        $this->assertContains('question', FaqResource::getTranslatableAttributes());
        $this->assertContains('answer', FaqResource::getTranslatableAttributes());

        $this->assertContains('title', SlideResource::getTranslatableAttributes());
    }
}
