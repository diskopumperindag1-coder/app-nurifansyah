<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make(__('Page Content'))
                                ->schema([
                                    TextInput::make('title')
                                        ->label(__('Page Title'))
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (string $operation, ?string $state, callable $set) {
                                            if ($operation === 'create' && filled($state)) {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),
                                    TextInput::make('slug')
                                        ->label(__('URL Slug'))
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true),
                                    RichEditor::make('content')
                                        ->label(__('Content'))
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(2),

                        Group::make([
                            Section::make(__('Publication & Menu'))
                                ->schema([
                                    Select::make('status')
                                        ->label(__('Status'))
                                        ->options(PageStatus::class)
                                        ->default(PageStatus::Published->value)
                                        ->required(),
                                    TextInput::make('sort_order')
                                        ->label(__('Display Order'))
                                        ->numeric()
                                        ->default(0),
                                    Toggle::make('show_in_menu')
                                        ->label(__('Show in Navigation'))
                                        ->helperText(__('Available to be attached to menu'))
                                        ->default(true),
                                    Hidden::make('user_id')
                                        ->default(fn () => auth()->id()),
                                ]),

                            Section::make(__('Cover Image (Optional)'))
                                ->schema([
                                    FileUpload::make('image')
                                        ->label(__('Page Image'))
                                        ->image()
                                        ->disk('public')
                                        ->directory('pages')
                                        ->imageEditor()
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(1),
                    ])->columnSpanFull(),
            ]);
    }
}
