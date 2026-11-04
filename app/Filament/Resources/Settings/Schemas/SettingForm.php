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
                    ->label('Grup Pengaturan')
                    ->options([
                        'general' => 'Umum (General)',
                        'contact' => 'Kontak & Alamat',
                        'social' => 'Media Sosial',
                        'appearance' => 'Tampilan & Logo',
                        'seo' => 'SEO & Meta',
                    ])
                    ->default('general')
                    ->required(),
                TextInput::make('key')
                    ->label('Kunci (Key)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('contoh: site_name, office_phone, instagram_url'),
                Select::make('type')
                    ->label('Tipe Data')
                    ->options([
                        'text' => 'Teks Singkat',
                        'textarea' => 'Teks Panjang',
                        'image' => 'URL / Path Gambar',
                        'boolean' => 'Boolean (0 atau 1)',
                        'json' => 'JSON Data',
                    ])
                    ->default('text')
                    ->required(),
                Textarea::make('value')
                    ->label('Nilai (Value)')
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder('Masukkan isi nilai konfigurasi...'),
            ]);
    }
}
