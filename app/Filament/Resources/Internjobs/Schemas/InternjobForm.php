<?php

namespace App\Filament\Resources\Internjobs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InternjobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('position')
                    ->placeholder('Contoh: Software Engineer Intern')
                    ->required(),
                TextInput::make('company')
                    ->placeholder('Contoh: PT. ABC Indonesia')
                    ->required(),
                TextInput::make('location')
                    ->placeholder('Contoh: Jakarta, Indonesia')
                    ->required(),
                TextInput::make('salary_min')
                    ->placeholder('Contoh: 2000000')
                    ->numeric()
                    ->default(null),
                TextInput::make('salary_max')
                    ->placeholder('Contoh: 3000000')
                    ->numeric()
                    ->default(null),
                Textarea::make('description')
                    ->placeholder('Deskripsikan posisi magang secara detail...')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('responsibility')
                    ->placeholder('Tanggung jawab utama dalam posisi ini...')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('qualifications')
                    ->placeholder('Kualifikasi yang dibutuhkan...')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('deadline'),
                FileUpload::make('logo')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->imageResizeMode('cover')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(2048)
                    ->directory('logos')
                    ->disk('public')
                    ->hint('Ukuran gambar yang disarankan: 200x200 piksel untuk hasil terbaik.')
                    ->required(false),
                Select::make('category')
                    ->label('Fakultas')
                    ->options([
                        'Fakultas Teknik' => 'Fakultas Teknik',
                        'Fakultas Ekonomi dan Bisnis' => 'Fakultas Ekonomi dan Bisnis',
                        'Fakultas Ilmu Komputer' => 'Fakultas Ilmu Komputer',
                        'Fakultas Hukum' => 'Fakultas Hukum',
                        'Fakultas Kesehatan' => 'Fakultas Kesehatan',
                        'Fakultas Pertanian' => 'Fakultas Pertanian',
                        'Fakultas Ilmu Sosial dan Politik' => 'Fakultas Ilmu Sosial dan Politik',
                        'Fakultas Keguruan dan Ilmu Pendidikan' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                        'Fakultas Agama Islam' => 'Fakultas Agama Islam',
                    ])
                    ->required()
                    ->placeholder('Pilih Fakultas'),
                TextInput::make('apply_url')
                    ->label('Apply URL')
                    ->url()
                    ->placeholder('https://example.com/apply'),
            ]);
    }
}
