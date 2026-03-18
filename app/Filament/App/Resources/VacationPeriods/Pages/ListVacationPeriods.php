<?php

namespace App\Filament\App\Resources\VacationPeriods\Pages;

use App\Filament\App\Resources\VacationPeriods\VacationPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVacationPeriods extends ListRecords
{
    protected static string $resource = VacationPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
