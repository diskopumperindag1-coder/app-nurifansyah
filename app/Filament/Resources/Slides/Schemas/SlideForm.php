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
                            Section::make('Informasi Banner')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Judul Slide')
                                        ->required()
                                        ->maxLength(255),
                                    Textarea::make('description')
                                        ->label('Deskripsi / Keterangan Singkat')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                    TextInput::make('link_url')
                                        ->label('Tautan Tujuan (Link URL)')
                                        ->placeholder('https://... atau /posts/judul-artikel'),
                                ]),
                            Section::make('Gambar Banner')
                                ->schema([
                                    FileUpload::make('image')
                                        ->label('Gambar Slide')
                                        ->image()
                                        ->disk('public')
                                        ->directory('slides')
                                        ->imageEditor()
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(2),

                        Group::make([
                            Section::make('Pengaturan Tampil')
                                ->schema([
                                    TextInput::make('sort_order')
                                        ->label('Urutan')
                                        ->numeric()
                                        ->default(0)
                                        ->required(),
                                    Toggle::make('is_active')
                                        ->label('Status Aktif')
                                        ->default(true),
                                    DatePicker::make('start_date')
                                        ->label('Mulai Tayang'),
                                    DatePicker::make('end_date')
                                        ->label('Selesai Tayang'),
                                ]),
                        ])->columnSpan(1),
                    ])->columnSpanFull(),
            ]);
    }
}
