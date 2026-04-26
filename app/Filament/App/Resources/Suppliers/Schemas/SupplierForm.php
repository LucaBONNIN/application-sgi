<?php

namespace App\Filament\App\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label(__('filament/resources/supplier.fields.name')),
                TextInput::make('email')
                    ->email(),
                TextInput::make('phone')
                    ->tel()
                    ->label(__('filament/resources/supplier.fields.phone')),
                TextInput::make('website_url')
                    ->url()
                    ->label(__('filament/resources/supplier.fields.website_url')),
                Textarea::make('address')
                    ->rows(3)
                    ->label(__('filament/resources/supplier.fields.address')),
                TextInput::make('siret')
                    ->label('SIRET'),
            ]);
    }
}
