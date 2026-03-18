<?php

namespace App\Filament\App\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Services\OrderAgeCalculator;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrdersTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label(__('filament/resources/order.fields.user'))
                    ->searchable()
                    ->sortable(),

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
                    ->badge()
                    ->sortable(),

                TextColumn::make('lines_sum_total_price')
                    ->label(__('filament/resources/order.fields.total_amount'))
                    ->sum('lines', 'total_price')
                    ->numeric()
                    ->formatStateUsing(fn ($state): string => number_format($state / 100, 2, ',', ' ').' €'),

                TextColumn::make('age')
                    ->label(__('filament/resources/order.fields.age'))
                    ->state(function (Model $record): ?int {
                        if ($record->status->isTerminal()) {
                            return null;
                        }

                        return app(OrderAgeCalculator::class)
                            ->calculateBusinessDays($record->created_at, now());
                    })
                    ->suffix(' j'),

                TextColumn::make('estimated_delivery_date')
                    ->label(__('filament/resources/order.fields.estimated_delivery_date'))
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->label(__('filament/resources/order.fields.description'))
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament/resources/order.fields.status'))
                    ->options(OrderStatus::class)
                    ->multiple(),

                SelectFilter::make('service')
                    ->label(__('filament/resources/order.fields.service'))
                    ->relationship('service', 'name'),

                SelectFilter::make('supplier')
                    ->label(__('filament/resources/order.fields.supplier'))
                    ->relationship('supplier', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
