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
                            Section::make('Konten Halaman')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Judul Halaman')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (string $operation, ?string $state, callable $set) {
                                            if ($operation === 'create' && filled($state)) {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),
                                    TextInput::make('slug')
                                        ->label('Slug URL')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true),
                                    RichEditor::make('content')
                                        ->label('Isi Konten')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(2),

                        Group::make([
                            Section::make('Publikasi & Menu')
                                ->schema([
                                    Select::make('status')
                                        ->label('Status')
                                        ->options(PageStatus::class)
                                        ->default(PageStatus::Published->value)
                                        ->required(),
                                    TextInput::make('sort_order')
                                        ->label('Urutan Tampilan')
                                        ->numeric()
                                        ->default(0),
                                    Toggle::make('show_in_menu')
                                        ->label('Tampilkan di Navigasi')
                                        ->helperText('Tersedia untuk dipasang pada menu')
                                        ->default(true),
                                    Hidden::make('user_id')
                                        ->default(fn () => auth()->id()),
                                ]),

                            Section::make('Gambar Sampul (Opsional)')
                                ->schema([
                                    FileUpload::make('image')
                                        ->label('Gambar Halaman')
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
