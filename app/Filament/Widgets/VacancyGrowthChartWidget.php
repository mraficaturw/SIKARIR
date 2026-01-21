<?php

namespace App\Filament\Widgets;

use App\Models\Vacancy;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VacancyGrowthChartWidget extends ChartWidget
{
    public function getHeading(): string
    {
        return 'Vacancy Growth per Year';
    }

    protected function getData(): array
    {
        $data = Vacancy::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('count', 'year')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Vacancies',
                    'data' => array_values($data),
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
