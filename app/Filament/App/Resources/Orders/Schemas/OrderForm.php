<?php

namespace App\Filament\App\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Models\Budget;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = auth()->user()->can('BypassOwnership:Order');

        return $schema
            ->components([
                Section::make(__('filament/resources/order.sections.general'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        Select::make('user_id')
                            ->label(__('filament/resources/order.fields.user'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(auth()->id())
                            ->visible($isAdmin),

                        Hidden::make('user_id')
                            ->default(auth()->id())
                            ->visible(! $isAdmin),

                        Select::make('service_id')
                            ->label(__('filament/resources/order.fields.service'))
                            ->relationship(
                                'service',
                                'name',
                                modifyQueryUsing: $isAdmin
                                    ? null
                                    : fn ($query) => $query->whereHas('users', fn ($q) => $q->where('users.id', auth()->id()))
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),

                        Select::make('supplier_id')
                            ->label(__('filament/resources/order.fields.supplier'))
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        FileUpload::make('quotation_path')
                            ->label(__('filament/resources/order.fields.quotation'))
                            ->disk('local')
                            ->directory('quotations')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->live()
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label(__('filament/resources/order.fields.description'))
                            ->columnSpanFull(),
                    ]),

                Section::make(__('filament/resources/order.sections.admin'))
                    ->columnSpanFull()
                    ->visible($isAdmin)
                    ->columns(2)
                    ->components([
                        Select::make('budget_id')
                            ->label(__('filament/resources/order.fields.budget'))
                            ->options(function (Get $get) {
                                $serviceId = $get('service_id');

                                if (! $serviceId) {
                                    return [];
                                }

                                return Budget::query()
                                    ->where('service_id', $serviceId)
                                    ->get()
                                    ->pluck('budget_name', 'id');
                            })
                            ->searchable()
                            ->preload(),

                        Select::make('status')
                            ->label(__('filament/resources/order.fields.status'))
                            ->options(OrderStatus::class)
                            ->default(OrderStatus::Sent),

                        DatePicker::make('estimated_delivery_date')
                            ->label(__('filament/resources/order.fields.estimated_delivery_date')),
                    ]),

                Section::make(__('filament/resources/order.sections.products'))
                    ->columnSpanFull()
                    ->visible(fn (string $operation): bool => $operation === 'create')
                    ->components([
                        Repeater::make('lines')
                            ->label(__('filament/resources/order.fields.lines.label'))
                            ->addActionLabel(__('filament/resources/order.fields.lines.add_action'))
                            ->relationship()
                            ->minItems(1)
                            ->defaultItems(1)
                            ->columns(4)
                            ->components([
                                Select::make('category_id')
                                    ->label(__('filament/resources/order.fields.lines.category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('reference')
                                    ->label(__('filament/resources/order.fields.lines.reference'))
                                    ->required(fn (Get $get): bool => filled($get('../../quotation_path'))),

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
                                        $set('total_price', (int) $get('quantity') * (int) $get('unit_price'));
                                    }),

                                TextInput::make('unit_price')
                                    ->label(__('filament/resources/order.fields.lines.unit_price'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(fn (Get $get): bool => filled($get('../../quotation_path')))
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set): void {
                                        $set('total_price', (int) $get('quantity') * (int) $get('unit_price'));
                                    }),

                                TextInput::make('total_price')
                                    ->label(__('filament/resources/order.fields.lines.total_price'))
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),
                            ]),
                    ]),
            ]);
    }
}
