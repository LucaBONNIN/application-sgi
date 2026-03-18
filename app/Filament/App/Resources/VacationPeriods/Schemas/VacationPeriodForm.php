<?php

namespace App\Filament\App\Resources\VacationPeriods\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VacationPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament/resources/vacation_period.fields.name'))
                    ->required(),
                DatePicker::make('start_date')
                    ->label(__('filament/resources/vacation_period.fields.start_date'))
                    ->required(),
                DatePicker::make('end_date')
                    ->label(__('filament/resources/vacation_period.fields.end_date'))
                    ->required()
                    ->afterOrEqual('start_date'),
                TextInput::make('school_year')
                    ->label(__('filament/resources/vacation_period.fields.school_year'))
                    ->required()
                    ->numeric()
                    ->minValue(2020)
                    ->maxValue(2100),
            ]);
    }
}
