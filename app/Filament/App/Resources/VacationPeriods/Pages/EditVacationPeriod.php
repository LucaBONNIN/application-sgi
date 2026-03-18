<?php

namespace App\Filament\App\Resources\VacationPeriods\Pages;

use App\Filament\App\Resources\VacationPeriods\VacationPeriodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVacationPeriod extends EditRecord
{
    protected static string $resource = VacationPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
