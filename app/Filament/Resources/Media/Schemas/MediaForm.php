<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Unggah Berkas Media')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Pilih Berkas / Gambar')
                            ->disk('public')
                            ->directory('media')
                            ->required()
                            ->imagePreviewHeight('250')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state instanceof TemporaryUploadedFile) {
                                    $set('file_name', $state->getClientOriginalName());
                                    $set('mime_type', $state->getMimeType());
                                    $set('file_size', $state->getSize());
                                }
                            }),
                        TextInput::make('file_name')
                            ->label('Nama Berkas')
                            ->required(),
                        TextInput::make('alt_text')
                            ->label('Teks Alternatif (Alt Text)')
                            ->placeholder('Deskripsi gambar untuk aksesibilitas...'),
                        Hidden::make('mime_type'),
                        Hidden::make('file_size'),
                        Hidden::make('disk')->default('public'),
                        Hidden::make('user_id')->default(fn () => auth()->id()),
                    ]),
            ]);
    }
}
