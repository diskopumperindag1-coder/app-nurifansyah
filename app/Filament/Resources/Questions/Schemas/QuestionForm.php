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
                Section::make(__('Inquirer Data'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Full Name'))
                                    ->required(),
                                TextInput::make('email')
                                    ->label(__('Email Address'))
                                    ->email()
                                    ->required(),
                                TextInput::make('phone')
                                    ->label(__('Phone / WhatsApp Number'))
                                    ->tel(),
                            ]),
                    ]),

                Section::make(__('Question Details'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('topic_id')
                                    ->label(__('Question Topic'))
                                    ->relationship('topic', 'name')
                                    ->required(),
                                Select::make('status')
                                    ->label(__('Question Status'))
                                    ->options(QuestionStatus::class)
                                    ->default(QuestionStatus::Pending->value)
                                    ->required(),
                            ]),
                        TextInput::make('subject')
                            ->label(__('Subject / Title'))
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('body')
                            ->label(__('Full Question Body'))
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('ip_address')
                            ->label(__('Sender IP Address'))
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
