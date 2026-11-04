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
            Stat::make(__('Total Articles'), $totalPosts)
                ->description(__(':count articles published', ['count' => $publishedPosts]))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make(__('Pending Questions'), $pendingQuestions)
                ->description($pendingQuestions > 0 ? __('Needs officer review') : __('All questions answered'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'success'),

            Stat::make(__('Answered Questions'), $answeredQuestions)
                ->description(__('Responses completed'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(__('Pages & FAQs'), __(':pages Pages / :faqs FAQs', ['pages' => $totalPages, 'faqs' => $totalFaqs]))
                ->description(__('Active static information'))
                ->descriptionIcon('heroicon-m-information-circle')
                ->color('info'),
        ];
    }
}
