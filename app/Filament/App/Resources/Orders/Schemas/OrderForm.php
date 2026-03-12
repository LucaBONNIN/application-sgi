<?php

namespace App\Filament\App\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Service'),

                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('budget_id')
                    ->integer(),

                Select::make('status')
                    ->options(OrderStatus::class),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->nullable(),

                FileUpload::make('quotation_path')
                    ->label('Devis (fichier)')
                    ->directory('quotations')
                    ->nullable(),
            ]);
    }
}
