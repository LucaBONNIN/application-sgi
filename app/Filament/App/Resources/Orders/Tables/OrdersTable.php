<?php

namespace App\Filament\App\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('budget_id'),

                TextColumn::make('status'),

                TextColumn::make('description'),

                TextColumn::make('quotation_path'),

                TextColumn::make('estimated_delivery_date')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
