<?php

namespace App\Filament\App\Resources\Budgets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class BudgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->required()
                    ->live(),
                TextInput::make('year')
                    ->required()
                    ->numeric()
                    ->rules(fn (Get $get, ?string $operation, $record): array => [
                        Rule::unique('budgets', 'year')
                            ->where('service_id', $get('service_id'))
                            ->ignore($record),
                    ]),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->suffix('€')
                    ->formatStateUsing(fn ($state): ?string => $state !== null ? number_format($state / 100, 2, '.', '') : null)
                    ->dehydrateStateUsing(fn ($state): ?int => $state !== null ? (int) round((float) $state * 100) : null),
            ]);
    }
}
