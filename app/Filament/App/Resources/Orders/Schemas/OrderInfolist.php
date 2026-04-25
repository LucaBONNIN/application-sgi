<?php

namespace App\Filament\App\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('#'),

                TextEntry::make('user.name')
                    ->label(__('filament/resources/order.fields.user')),

                TextEntry::make('service.name')
                    ->label(__('filament/resources/order.fields.service')),

                TextEntry::make('supplier.name')
                    ->label(__('filament/resources/order.fields.supplier')),

                TextEntry::make('budget.budgetName')
                    ->label(__('filament/resources/order.fields.budget')),

                TextEntry::make('status')
                    ->label(__('filament/resources/order.fields.status'))
                    ->badge(),

                TextEntry::make('description')
                    ->label(__('filament/resources/order.fields.description'))
                    ->columnSpanFull(),

                TextEntry::make('quotation_path')
                    ->label(__('filament/resources/order.fields.quotation'))
                    ->formatStateUsing(fn (string $state): string => basename($state))
                    ->url(fn (Order $record): string => route('orders.quotation.download', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Order $record): bool => filled($record->quotation_path)),

                TextEntry::make('estimated_delivery_date')
                    ->label(__('filament/resources/order.fields.estimated_delivery_date'))
                    ->date(),

                TextEntry::make('created_at')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
