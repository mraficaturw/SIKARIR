<?php

namespace App\Filament\Resources\Internjobs;

use App\Filament\Resources\Internjobs\Pages\CreateInternjob;
use App\Filament\Resources\Internjobs\Pages\EditInternjob;
use App\Filament\Resources\Internjobs\Pages\ListInternjobs;
use App\Filament\Resources\Internjobs\Schemas\InternjobForm;
use App\Filament\Resources\Internjobs\Tables\InternjobsTable;
use App\Models\Internjob;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InternjobResource extends Resource
{
    protected static ?string $model = Internjob::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return InternjobForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InternjobsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInternjobs::route('/'),
            'create' => CreateInternjob::route('/create'),
            'edit' => EditInternjob::route('/{record}/edit'),
        ];
    }
}
