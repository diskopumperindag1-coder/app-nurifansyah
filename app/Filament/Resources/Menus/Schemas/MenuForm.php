<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Menu Information'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Menu Name'))
                                    ->required()
                                    ->placeholder(__('e.g. Main Menu, Footer Menu')),
                                Select::make('location')
                                    ->label(__('Placement Location'))
                                    ->options([
                                        'header' => __('Header (Top Navigation)'),
                                        'footer' => __('Footer (Bottom Navigation)'),
                                        'sidebar' => __('Sidebar'),
                                    ])
                                    ->required()
                                    ->default('header'),
                            ]),
                        Toggle::make('is_active')
                            ->label(__('Active Status'))
                            ->default(true),
                    ]),

                Section::make(__('Menu Items List'))
                    ->description(__('Manage links and order of items in this menu'))
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextInput::make('label')
                                            ->label(__('Item Label'))
                                            ->required(),
                                        Select::make('type')
                                            ->label(__('Link Type'))
                                            ->options([
                                                'custom' => __('Custom / External Link'),
                                                'page' => __('Static Page'),
                                                'post' => __('Post'),
                                                'category' => __('News Category'),
                                            ])
                                            ->default('custom')
                                            ->required(),
                                        TextInput::make('url')
                                            ->label(__('URL / Path'))
                                            ->required()
                                            ->placeholder(__('e.g. /about-us or https://...')),
                                        Toggle::make('is_active')
                                            ->label(__('Active'))
                                            ->default(true)
                                            ->inline(false),
                                    ]),
                            ])
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
