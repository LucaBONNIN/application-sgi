<?php

namespace App\Filament\App\Resources\Orders\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
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
                TextInput::make('order_id')
                    ->required()
                    ->integer(),

                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable(),

                TextInput::make('reference'),

                TextInput::make('designation')
                    ->required(),

                TextInput::make('quantity')
                    ->required()
                    ->integer(),

                TextInput::make('unit_price')
                    ->integer(),

                TextInput::make('total_price')
                    ->integer(),

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
                TextEntry::make('order_id'),

                TextEntry::make('category.name'),

                TextEntry::make('reference'),

                TextEntry::make('designation'),

                TextEntry::make('quantity'),

                TextEntry::make('unit_price'),

                TextEntry::make('total_price'),

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
                TextColumn::make('order_id'),

                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('reference'),

                TextColumn::make('designation'),

                TextColumn::make('quantity'),

                TextColumn::make('unit_price'),

                TextColumn::make('total_price'),
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
