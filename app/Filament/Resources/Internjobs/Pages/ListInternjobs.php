<?php

namespace App\Filament\Resources\Internjobs\Pages;

use App\Filament\Resources\Internjobs\InternjobResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListInternjobs extends ListRecords
{
    protected static string $resource = InternjobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->successNotificationTitle('Created!'),
        ];
    }

    public function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
