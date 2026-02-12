<?php

namespace App\Filament\App\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->required(),

                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('budget_id')
                    ->integer(),

                TextInput::make('status')
                    ->required()
                    ->integer(),

                TextInput::make('description'),

                TextInput::make('quotation_path'),

                DatePicker::make('estimated_delivery_date'),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->dateTime(),
            ]);
    }
}
