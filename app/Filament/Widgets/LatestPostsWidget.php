<?php

namespace App\Filament\Widgets;

use App\Enums\PostStatus;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestPostsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string|Htmlable|null
    {
        return __('Latest Articles');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Latest Articles'))
            ->query(
                fn (): Builder => Post::query()
                    ->with('category')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Photo'))
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label(__('Article Title'))
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->badge()
                    ->color('primary'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (PostStatus $state): string => $state->getColor()),
                TextColumn::make('view_count')
                    ->label(__('Views'))
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('d M Y H:i'),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('edit')
                    ->label(__('Edit'))
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
