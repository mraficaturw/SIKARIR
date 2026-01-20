<?php

namespace App\Filament\Widgets;

use App\Models\Internjob;
use App\Models\Companies;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Vacancies', Internjob::count())
                ->description('Total number of vacancies')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Partner', Companies::count())
                ->description('Total number of partner')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
