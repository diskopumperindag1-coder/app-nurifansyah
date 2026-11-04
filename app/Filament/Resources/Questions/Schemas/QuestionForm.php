<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\QuestionStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Penanya')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Alamat Email')
                                    ->email()
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('Nomor Telepon / WhatsApp')
                                    ->tel(),
                            ]),
                    ]),

                Section::make('Rincian Pertanyaan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('topic_id')
                                    ->label('Topik Pertanyaan')
                                    ->relationship('topic', 'name')
                                    ->required(),
                                Select::make('status')
                                    ->label('Status Pertanyaan')
                                    ->options(QuestionStatus::class)
                                    ->default(QuestionStatus::Pending->value)
                                    ->required(),
                            ]),
                        TextInput::make('subject')
                            ->label('Judul / Subjek')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('body')
                            ->label('Isi Pertanyaan Lengkap')
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('ip_address')
                            ->label('IP Address Pengirim')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
