<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Account Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Full Name'))
                            ->required(),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText(__('Leave blank if you do not want to change the password.')),
                        Select::make('role')
                            ->label(__('Access Role'))
                            ->options(UserRole::class)
                            ->default(UserRole::Editor->value)
                            ->required(),
                        FileUpload::make('avatar')
                            ->label(__('Profile Photo (Avatar)'))
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('avatars'),
                    ]),
            ]);
    }
}
