<?php

namespace App\Filament\Resources\Internjobs\Pages;

use App\Filament\Resources\Internjobs\InternjobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInternjob extends CreateRecord
{
    public function getRedirectUrl(): string
{
    return static::getResource()::getUrl('index');
}
    protected static string $resource = InternjobResource::class;
}
