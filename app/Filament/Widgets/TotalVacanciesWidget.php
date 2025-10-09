<?php

namespace App\Filament\Widgets;

use App\Models\internjob;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalVacanciesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Vacancies', internjob::count())
                ->description('Total number of vacancies')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
