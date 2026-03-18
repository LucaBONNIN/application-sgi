<?php

namespace App\Filament\App\Resources\VacationPeriods;

use App\Filament\App\Resources\VacationPeriods\Pages\CreateVacationPeriod;
use App\Filament\App\Resources\VacationPeriods\Pages\EditVacationPeriod;
use App\Filament\App\Resources\VacationPeriods\Pages\ListVacationPeriods;
use App\Filament\App\Resources\VacationPeriods\Schemas\VacationPeriodForm;
use App\Filament\App\Resources\VacationPeriods\Tables\VacationPeriodsTable;
use App\Models\VacationPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VacationPeriodResource extends Resource
{
    protected static ?string $model = VacationPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::CalendarDays;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.admin');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/vacation_period.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/vacation_period.label');
    }

    public static function form(Schema $schema): Schema
    {
        return VacationPeriodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VacationPeriodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVacationPeriods::route('/'),
            'create' => CreateVacationPeriod::route('/create'),
            'edit' => EditVacationPeriod::route('/{record}/edit'),
        ];
    }
}
