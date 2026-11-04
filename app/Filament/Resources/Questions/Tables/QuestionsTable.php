<?php

namespace App\Filament\Resources\Questions\Tables;

use App\Enums\QuestionStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label('Subjek Pertanyaan')
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->limit(45),
                TextColumn::make('name')
                    ->label('Penanya')
                    ->searchable()
                    ->description(fn ($record): string => $record->email.($record->phone ? ' | '.$record->phone : '')),
                TextColumn::make('topic.name')
                    ->label('Topik')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (QuestionStatus $state): string => $state->getColor()),
                TextColumn::make('created_at')
                    ->label('Diajukan Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(QuestionStatus::class),
                SelectFilter::make('topic_id')
                    ->label('Topik')
                    ->relationship('topic', 'name'),
            ])
            ->recordActions([
                EditAction::make()->label('Tinjau & Jawab'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
