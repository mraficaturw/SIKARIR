<?php

namespace App\Filament\Resources\Vacancies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class VacancyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('company.company_name')
                    ->label('Company')
                    ->searchable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Fakultas')
                    ->searchable(),
                TextColumn::make('apply_url')
                    ->label('Apply URL')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('deadline', direction: 'asc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->successNotificationTitle('Deleted'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
