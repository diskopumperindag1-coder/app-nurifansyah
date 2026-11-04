<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make(__('Banner Information'))
                                ->schema([
                                    TextInput::make('title')
                                        ->label(__('Slide Title'))
                                        ->required()
                                        ->maxLength(255),
                                    Textarea::make('description')
                                        ->label(__('Description / Short Notes'))
                                        ->rows(3)
                                        ->columnSpanFull(),
                                    TextInput::make('link_url')
                                        ->label(__('Target Link (URL)'))
                                        ->placeholder(__('https://... or /posts/article-title')),
                                ]),
                            Section::make(__('Banner Image'))
                                ->schema([
                                    FileUpload::make('image')
                                        ->label(__('Slide Image'))
                                        ->image()
                                        ->disk('public')
                                        ->directory('slides')
                                        ->imageEditor()
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(2),

                        Group::make([
                            Section::make(__('Display Settings'))
                                ->schema([
                                    TextInput::make('sort_order')
                                        ->label(__('Sort Order'))
                                        ->numeric()
                                        ->default(0)
                                        ->required(),
                                    Toggle::make('is_active')
                                        ->label(__('Active Status'))
                                        ->default(true),
                                    DatePicker::make('start_date')
                                        ->label(__('Start Date')),
                                    DatePicker::make('end_date')
                                        ->label(__('End Date')),
                                ]),
                        ])->columnSpan(1),
                    ])->columnSpanFull(),
            ]);
    }
}
