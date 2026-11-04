<?php

namespace Tests\Feature;

use App\Enums\AnswerStatus;
use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\Menus\MenuResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Questions\QuestionResource;
use App\Filament\Resources\QuestionTopics\QuestionTopicResource;
use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Resources\Slides\SlideResource;
use App\Filament\Resources\Users\UserResource;
use Tests\TestCase;

class FilamentTranslationTest extends TestCase
{
    public function test_resources_and_enums_are_translated_in_indonesian(): void
    {
        app()->setLocale('id');

        $this->assertSame('Kategori', CategoryResource::getModelLabel());
        $this->assertSame('Kategori', CategoryResource::getPluralModelLabel());
        $this->assertSame('Kategori', CategoryResource::getNavigationLabel());
        $this->assertSame('CMS', CategoryResource::getNavigationGroup());

        $this->assertSame('FAQ', FaqResource::getModelLabel());
        $this->assertSame('Daftar FAQ', FaqResource::getPluralModelLabel());
        $this->assertSame('Tanya Jawab (FAQ)', FaqResource::getNavigationLabel());
        $this->assertSame('Layanan Publik', FaqResource::getNavigationGroup());

        $this->assertSame('Media', MediaResource::getModelLabel());
        $this->assertSame('Media Library', MediaResource::getNavigationLabel());
        $this->assertSame('CMS', MediaResource::getNavigationGroup());

        $this->assertSame('Menu', MenuResource::getModelLabel());
        $this->assertSame('Navigasi / Menu', MenuResource::getNavigationLabel());

        $this->assertSame('Halaman', PageResource::getModelLabel());
        $this->assertSame('Halaman Statis', PageResource::getNavigationLabel());

        $this->assertSame('Artikel', PostResource::getModelLabel());
        $this->assertSame('Artikel & Berita', PostResource::getNavigationLabel());

        $this->assertSame('Pertanyaan', QuestionResource::getModelLabel());
        $this->assertSame('Pertanyaan Masyarakat', QuestionResource::getNavigationLabel());
        $this->assertSame('Layanan Publik', QuestionResource::getNavigationGroup());

        $this->assertSame('Topik', QuestionTopicResource::getModelLabel());
        $this->assertSame('Topik Pertanyaan', QuestionTopicResource::getNavigationLabel());

        $this->assertSame('Pengaturan', SettingResource::getModelLabel());
        $this->assertSame('Pengaturan Situs', SettingResource::getNavigationLabel());
        $this->assertSame('Pengaturan', SettingResource::getNavigationGroup());

        $this->assertSame('Slide', SlideResource::getModelLabel());
        $this->assertSame('Slide Banner', SlideResource::getNavigationLabel());

        $this->assertSame('Pengguna', UserResource::getModelLabel());
        $this->assertSame('Pengguna & Role', UserResource::getNavigationLabel());
        $this->assertSame('Pengaturan', UserResource::getNavigationGroup());

        $this->assertSame('Diterbitkan', PageStatus::Published->getLabel());
        $this->assertSame('Diarsipkan', PostStatus::Archived->getLabel());
        $this->assertSame('Menunggu', QuestionStatus::Pending->getLabel());
        $this->assertSame('Ditolak', AnswerStatus::Rejected->getLabel());
        $this->assertSame('Administrator', UserRole::Admin->getLabel());
    }

    public function test_resources_and_enums_are_translated_in_english(): void
    {
        app()->setLocale('en');

        $this->assertSame('Category', CategoryResource::getModelLabel());
        $this->assertSame('Categories', CategoryResource::getPluralModelLabel());
        $this->assertSame('Categories', CategoryResource::getNavigationLabel());
        $this->assertSame('CMS', CategoryResource::getNavigationGroup());

        $this->assertSame('FAQ', FaqResource::getModelLabel());
        $this->assertSame('FAQs', FaqResource::getPluralModelLabel());
        $this->assertSame('Q&A (FAQ)', FaqResource::getNavigationLabel());
        $this->assertSame('Public Services', FaqResource::getNavigationGroup());

        $this->assertSame('Media', MediaResource::getModelLabel());
        $this->assertSame('Media Library', MediaResource::getNavigationLabel());
        $this->assertSame('CMS', MediaResource::getNavigationGroup());

        $this->assertSame('Menu', MenuResource::getModelLabel());
        $this->assertSame('Navigation / Menus', MenuResource::getNavigationLabel());

        $this->assertSame('Page', PageResource::getModelLabel());
        $this->assertSame('Static Pages', PageResource::getNavigationLabel());

        $this->assertSame('Article', PostResource::getModelLabel());
        $this->assertSame('Articles & News', PostResource::getNavigationLabel());

        $this->assertSame('Question', QuestionResource::getModelLabel());
        $this->assertSame('Public Questions', QuestionResource::getNavigationLabel());
        $this->assertSame('Public Services', QuestionResource::getNavigationGroup());

        $this->assertSame('Topic', QuestionTopicResource::getModelLabel());
        $this->assertSame('Question Topics', QuestionTopicResource::getNavigationLabel());

        $this->assertSame('Setting', SettingResource::getModelLabel());
        $this->assertSame('Site Settings', SettingResource::getNavigationLabel());
        $this->assertSame('Settings', SettingResource::getNavigationGroup());

        $this->assertSame('Slide', SlideResource::getModelLabel());
        $this->assertSame('Banner Slides', SlideResource::getNavigationLabel());

        $this->assertSame('User', UserResource::getModelLabel());
        $this->assertSame('Users & Roles', UserResource::getNavigationLabel());
        $this->assertSame('Settings', UserResource::getNavigationGroup());

        $this->assertSame('Published', PageStatus::Published->getLabel());
        $this->assertSame('Archived', PostStatus::Archived->getLabel());
        $this->assertSame('Pending', QuestionStatus::Pending->getLabel());
        $this->assertSame('Rejected', AnswerStatus::Rejected->getLabel());
        $this->assertSame('Administrator', UserRole::Admin->getLabel());
    }
}
