<?php

namespace App\Filament\Widgets;

use App\Enums\QuestionStatus;
use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Question;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestQuestionsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string|Htmlable|null
    {
        return __('Latest Incoming Questions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Latest Incoming Questions'))
            ->query(
                fn (): Builder => Question::query()
                    ->with('topic')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->weight('bold')
                    ->limit(40),
                TextColumn::make('name')
                    ->label(__('Inquirer Name')),
                TextColumn::make('topic.name')
                    ->label(__('Topic'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (QuestionStatus $state): string => $state->getColor()),
                TextColumn::make('created_at')
                    ->label(__('Received Time'))
                    ->since(),
            ])
            ->paginated(false)
            ->recordActions([
                Action::make('open')
                    ->label(__('Open'))
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
