<?php

namespace App\Filament\Resources\Vacancies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use App\Services\ImageService;

class VacancyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Position')
                    ->placeholder('Contoh: Software Engineer Intern')
                    ->required(),
                Select::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'company_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Pilih Perusahaan')
                    ->createOptionForm([
                        TextInput::make('company_name')
                            ->label('Nama Perusahaan')
                            ->required(),
                        FileUpload::make('logo')
                            ->label('Logo Perusahaan')
                            ->placeholder('Ukuran gambar yang disarankan: 200x200 piksel untuk hasil terbaik.')
                            ->disk('supabase')
                            ->directory('logos')
                            ->image()
                            ->imagePreviewHeight('100')
                            ->maxSize(5120)
                            ->visibility('public')
                            ->saveUploadedFileUsing(function ($file, $state, $set, $get) {
                                return ImageService::convertAndUpload(
                                    $file,
                                    'supabase',
                                    'logos',
                                    80
                                );
                            })
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('address')
                            ->label('Alamat Kantor')
                            ->required(),
                    ]),
                TextInput::make('location')
                    ->label('Internship Placement Location')
                    ->placeholder('Contoh: Jakarta, Indonesia')
                    ->required(),
                TextInput::make('salary_min')
                    ->placeholder('Contoh: 2000000')
                    ->numeric()
                    ->live()
                    ->rule(function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $max = $get('salary_max');
                            if ($value !== null && $max !== null && $value > $max) {
                                $fail("Salary min tidak boleh lebih besar dari salary max.");
                            }
                        };
                    })
                    ->default(null),
                TextInput::make('salary_max')
                    ->placeholder('Contoh: 3000000')
                    ->numeric()
                    ->live()
                    ->rule(function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $min = $get('salary_min');
                            if ($value !== null && $min !== null && $value < $min) {
                                $fail("Salary max tidak boleh lebih kecil dari salary min.");
                            }
                        };
                    })
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
                    ->label('Link Untuk Apply')
                    ->required()
                    ->url()
                    ->placeholder('https://example.com/apply'),
            ]);
    }
}
