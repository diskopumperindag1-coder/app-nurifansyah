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
                Section::make('Informasi Menu')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Menu')
                                    ->required()
                                    ->placeholder('contoh: Menu Utama, Menu Footer'),
                                Select::make('location')
                                    ->label('Lokasi Penempatan')
                                    ->options([
                                        'header' => 'Header (Navigasi Atas)',
                                        'footer' => 'Footer (Navigasi Bawah)',
                                        'sidebar' => 'Sidebar (Samping)',
                                    ])
                                    ->required()
                                    ->default('header'),
                            ]),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),

                Section::make('Daftar Item Menu')
                    ->description('Kelola tautan dan urutan item di dalam menu ini')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextInput::make('label')
                                            ->label('Label Item')
                                            ->required(),
                                        Select::make('type')
                                            ->label('Tipe Tautan')
                                            ->options([
                                                'custom' => 'Tautan Kustom / Eksternal',
                                                'page' => 'Halaman Statis',
                                                'post' => 'Artikel',
                                                'category' => 'Kategori Berita',
                                            ])
                                            ->default('custom')
                                            ->required(),
                                        TextInput::make('url')
                                            ->label('URL / Path')
                                            ->required()
                                            ->placeholder('/tentang-kami atau https://...'),
                                        Toggle::make('is_active')
                                            ->label('Aktif')
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
