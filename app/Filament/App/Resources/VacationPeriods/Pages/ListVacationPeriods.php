<?php

namespace App\Filament\App\Resources\VacationPeriods\Pages;

use App\Filament\App\Resources\VacationPeriods\VacationPeriodResource;
use App\Filament\App\Resources\VacationPeriods\Widgets\MyCalendarWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVacationPeriods extends ListRecords
{
    protected static string $resource = VacationPeriodResource::class;

    protected function getHeaderWidgets(): array
    {
        return [MyCalendarWidget::class];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
