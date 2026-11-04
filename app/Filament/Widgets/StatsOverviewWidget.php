<?php

namespace App\Filament\Widgets;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\Question;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::published()->count();
        $pendingQuestions = Question::pending()->count();
        $answeredQuestions = Question::answered()->count();
        $totalPages = Page::published()->count();
        $totalFaqs = Faq::active()->count();

        return [
            Stat::make('Total Artikel', $totalPosts)
                ->description("{$publishedPosts} artikel diterbitkan")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Pertanyaan Menunggu', $pendingQuestions)
                ->description($pendingQuestions > 0 ? 'Perlu ditinjau petugas' : 'Semua pertanyaan terjawab')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'success'),

            Stat::make('Pertanyaan Terjawab', $answeredQuestions)
                ->description('Respon selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Halaman & FAQ', "{$totalPages} Hal / {$totalFaqs} FAQ")
                ->description('Informasi statis aktif')
                ->descriptionIcon('heroicon-m-information-circle')
                ->color('info'),
        ];
    }
}
