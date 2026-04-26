<?php

namespace App\Filament\App\Resources\Orders\RelationManagers;

use App\Enums\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LinesRelationManager extends RelationManager
{
    protected static string $relationship = 'lines';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label(__('filament/resources/order.fields.lines.category'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                TextInput::make('reference')
                    ->label(__('filament/resources/order.fields.lines.reference')),

                TextInput::make('designation')
                    ->label(__('filament/resources/order.fields.lines.designation'))
                    ->required(),

                TextInput::make('quantity')
                    ->label(__('filament/resources/order.fields.lines.quantity'))
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(1)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $set('total_price', round((float) $get('quantity') * (float) $get('unit_price'), 2));
                    }),

                TextInput::make('unit_price')
                    ->label(__('filament/resources/order.fields.lines.unit_price'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->suffix('€')
                    ->formatStateUsing(fn ($state): ?string => $state !== null ? number_format($state / 100, 2, '.', '') : null)
                    ->dehydrateStateUsing(fn ($state): ?int => $state !== null ? (int) round((float) $state * 100) : null)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set): void {
                        $set('total_price', round((float) $get('quantity') * (float) $get('unit_price'), 2));
                    }),

                TextInput::make('total_price')
                    ->label(__('filament/resources/order.fields.lines.total_price'))
                    ->numeric()
                    ->suffix('€')
                    ->disabled()
                    ->dehydrated()
                    ->formatStateUsing(fn ($state): ?string => $state !== null ? number_format($state / 100, 2, '.', '') : null)
                    ->dehydrateStateUsing(fn ($state): ?int => $state !== null ? (int) round((float) $state * 100) : null)
                    ->default(0),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label(__('filament/resources/order.fields.lines.category')),

                TextEntry::make('reference')
                    ->label(__('filament/resources/order.fields.lines.reference')),

                TextEntry::make('designation')
                    ->label(__('filament/resources/order.fields.lines.designation')),

                TextEntry::make('quantity')
                    ->label(__('filament/resources/order.fields.lines.quantity')),

                TextEntry::make('unit_price')
                    ->label(__('filament/resources/order.fields.lines.unit_price'))
                    ->formatStateUsing(fn ($state): string => number_format($state / 100, 2, ',', ' ').' €'),

                TextEntry::make('total_price')
                    ->label(__('filament/resources/order.fields.lines.total_price'))
                    ->formatStateUsing(fn ($state): string => number_format($state / 100, 2, ',', ' ').' €'),

                TextEntry::make('created_at')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }

    public function table(Table $table): Table
    {
        $canModify = auth()->user()->can('BypassOwnership:Order')
            || $this->ownerRecord->status === OrderStatus::Sent;

        return $table
            ->recordTitleAttribute('designation')
            ->columns([
                TextColumn::make('category.name')
                    ->label(__('filament/resources/order.fields.lines.category'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('reference')
                    ->label(__('filament/resources/order.fields.lines.reference')),

                TextColumn::make('designation')
                    ->label(__('filament/resources/order.fields.lines.designation')),

                TextColumn::make('quantity')
                    ->label(__('filament/resources/order.fields.lines.quantity')),

                TextColumn::make('unit_price')
                    ->label(__('filament/resources/order.fields.lines.unit_price'))
                    ->formatStateUsing(fn ($state): string => number_format($state / 100, 2, ',', ' ').' €'),

                TextColumn::make('total_price')
                    ->label(__('filament/resources/order.fields.lines.total_price'))
                    ->formatStateUsing(fn ($state): string => number_format($state / 100, 2, ',', ' ').' €'),
            ])
            ->headerActions([
                CreateAction::make()->visible($canModify),
            ])
            ->recordActions([
                EditAction::make()->visible($canModify),
                DeleteAction::make()->visible($canModify),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible($canModify),
                ]),
            ]);
    }
}
