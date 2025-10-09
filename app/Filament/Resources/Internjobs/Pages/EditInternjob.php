<?php

namespace App\Filament\Resources\Internjobs\Pages;

use App\Filament\Resources\Internjobs\InternjobResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInternjob extends EditRecord
{
    protected static string $resource = InternjobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
