<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('group')
                    ->label(__('Settings Group'))
                    ->options([
                        'general' => __('General'),
                        'contact' => __('Contact & Address'),
                        'social' => __('Social Media'),
                        'appearance' => __('Appearance & Logo'),
                        'seo' => __('SEO & Meta'),
                    ])
                    ->default('general')
                    ->required(),
                TextInput::make('key')
                    ->label(__('Key'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder(__('e.g. site_name, office_phone, instagram_url')),
                Select::make('type')
                    ->label(__('Data Type'))
                    ->options([
                        'text' => __('Short Text'),
                        'textarea' => __('Long Text'),
                        'image' => __('Image URL / Path'),
                        'boolean' => __('Boolean (0 or 1)'),
                        'json' => __('JSON Data'),
                    ])
                    ->default('text')
                    ->required(),
                Textarea::make('value')
                    ->label(__('Value'))
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder(__('Enter configuration value...')),
            ]);
    }
}
