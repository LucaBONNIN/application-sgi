<?php

namespace App\Filament\App\Resources\Users\RelationManagers;

use App\Filament\App\Resources\Orders\OrderResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('#'),

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
                    ->label(__('filament/resources/order.fields.description')),

                TextEntry::make('estimated_delivery_date')
                    ->label(__('filament/resources/order.fields.estimated_delivery_date'))
                    ->date(),

                TextEntry::make('created_at')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('service.name')
                    ->label(__('filament/resources/order.fields.service'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier.name')
                    ->label(__('filament/resources/order.fields.supplier'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('filament/resources/order.fields.status'))
                    ->badge(),

                TextColumn::make('estimated_delivery_date')
                    ->label(__('filament/resources/order.fields.estimated_delivery_date'))
                    ->date(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => OrderResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
