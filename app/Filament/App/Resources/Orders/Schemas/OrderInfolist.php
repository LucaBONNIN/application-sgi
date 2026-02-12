<?php

namespace App\Filament\App\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id'),

                TextEntry::make('user.name'),

                TextEntry::make('service.name'),

                TextEntry::make('supplier.name'),

                TextEntry::make('budget_id'),

                TextEntry::make('status'),

                TextEntry::make('description'),

                TextEntry::make('quotation_path'),

                TextEntry::make('estimated_delivery_date')
                    ->dateTime(),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->dateTime(),
            ]);
    }
}
