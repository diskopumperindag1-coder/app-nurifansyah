<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label(__('Preview'))
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('/favicon.ico')),
                TextColumn::make('file_name')
                    ->label(__('File Name'))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label(__('Type'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('formatted_size')
                    ->label(__('Size')),
                TextColumn::make('alt_text')
                    ->label(__('Alt Text'))
                    ->limit(30)
                    ->color('gray'),
                TextColumn::make('created_at')
                    ->label(__('Uploaded At'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
