<?php

namespace App\Filament\App\Resources\Users\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id'),

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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
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
            ->headerActions([
                CreateAction::make(),
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
