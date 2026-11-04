<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Konten Artikel')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Judul Artikel')
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
                                    Textarea::make('summary')
                                        ->label('Ringkasan / Sinopsis')
                                        ->rows(3)
                                        ->columnSpanFull()
                                        ->placeholder('Ringkasan singkat untuk tampilan kartu preview berita...'),
                                    RichEditor::make('content')
                                        ->label('Isi Konten Lengkap')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(2),

                        Group::make([
                            Section::make('Publikasi & Kategori')
                                ->schema([
                                    Select::make('category_id')
                                        ->label('Kategori')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                    Select::make('status')
                                        ->label('Status')
                                        ->options(PostStatus::class)
                                        ->default(PostStatus::Draft->value)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state === PostStatus::Published->value) {
                                                $set('published_at', now()->toDateTimeString());
                                            }
                                        }),
                                    DateTimePicker::make('published_at')
                                        ->label('Tanggal Publikasi')
                                        ->default(now()),
                                    Toggle::make('is_featured')
                                        ->label('Artikel Unggulan (Featured)')
                                        ->helperText('Tampilkan di banner utama / headline')
                                        ->default(false),
                                    Hidden::make('user_id')
                                        ->default(fn () => auth()->id()),
                                ]),

                            Section::make('Gambar Utama')
                                ->schema([
                                    FileUpload::make('image')
                                        ->label('Featured Image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('posts')
                                        ->imageEditor()
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(1),
                    ])->columnSpanFull(),
            ]);
    }
}
