<?php

namespace App\Filament\Resources\Internjobs\Pages;

use App\Filament\Resources\Internjobs\InternjobResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInternjobs extends ListRecords
{
    protected static string $resource = InternjobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
