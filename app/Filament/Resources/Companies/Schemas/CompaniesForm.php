<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompaniesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->label('Nama Perusahaan')
                    ->placeholder('Contoh: PT. ABC Indonesia')
                    ->required(),
                TextInput::make('official_website')
                    ->label('Website Resmi')
                    ->placeholder('Contoh: https://www.abc.com')
                    ->url(),
                TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Contoh: info@abc.com')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->placeholder('Contoh: +62 812 3456 7890'),
                TextInput::make('address')
                    ->label('Alamat')
                    ->placeholder('Contoh: Jl. ABC No. 123, Jakarta')
                    ->columnSpanFull(),
                Textarea::make('company_description')
                    ->label('Deskripsi Perusahaan')
                    ->placeholder('Contoh: PT. ABC adalah perusahaan yang bergerak di bidang teknologi informasi.')
                    ->rows(4)
                    ->columnSpanFull(),
                FileUpload::make('logo')
                    ->label('Logo Perusahaan')
                    ->disk('supabase')
                    ->directory('logos')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->imageResizeMode('cover')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->visibility('public')
                    ->hint('Ukuran gambar yang disarankan: 200x200 piksel untuk hasil terbaik.')
                    ->saveUploadedFileUsing(function ($file, $state, $set, $get) {
                        return \App\Services\ImageService::convertAndUpload(
                            $file,
                            'supabase',
                            'logos',
                            80
                        );
                    })
                    ->columnSpanFull(),
            ]);
    }
}
