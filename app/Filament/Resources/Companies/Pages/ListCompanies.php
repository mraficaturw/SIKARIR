<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompaniesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompaniesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->successNotificationTitle('Created!'),
        ];
    }

    /**
     * -------------------------------------------------------------------------
     * Query Optimization: Eager Load Vacancy Count + Select Specific Columns
     * -------------------------------------------------------------------------
     * Prevent N+1 query problem when counting vacancies in table.
     * Also reduce data transfer by only selecting displayed columns.
     * 
     * WITHOUT this: 50 companies = 1 + 50 count queries = 51 queries
     * WITH this: 50 companies = 1 query with COUNT subquery
     * WITH select: Transfer only needed data
     * 
     * Performance gain: 98% reduction in queries + reduced data transfer
     */
    protected function modifyQueryUsing($query)
    {
        return $query
            ->withCount('vacancies')
            ->select([
                'id',
                'company_name',
                'email',
                'phone',
                'address',
                'official_website',
                'logo',
                'created_at'
            ]);
    }

    /**
     * -------------------------------------------------------------------------
     * Pagination Settings
     * -------------------------------------------------------------------------
     * Limit default records to improve initial page load
     */
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    protected function getDefaultTableRecordsPerPage(): int
    {
        return 10;  // Reduced from 25 to 10 for faster load
    }

    /**
     * -------------------------------------------------------------------------
     * Table Configuration Optimizations
     * -------------------------------------------------------------------------
     */
    protected function getTableDeferLoading(): bool
    {
        return false; // Set true for very large datasets
    }

    protected function getTablePoll(): ?string
    {
        return null; // Disable auto-refresh
    }
}
