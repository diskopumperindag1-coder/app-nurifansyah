<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\PostStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Image'))
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->description(fn ($record): ?string => $record->summary ? substr($record->summary, 0, 60).'...' : null),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (PostStatus $state): string => $state->getColor()),
                TextColumn::make('published_at')
                    ->label(__('Publication Date'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label(__('Views'))
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('is_featured')
                    ->label(__('Featured'))
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('user.name')
                    ->label(__('Author'))
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(PostStatus::class),
                TernaryFilter::make('is_featured')
                    ->label(__('Featured')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
