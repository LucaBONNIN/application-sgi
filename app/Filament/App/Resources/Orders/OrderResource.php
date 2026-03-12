<?php

namespace App\Filament\App\Resources\Orders;

use App\Filament\App\Resources\Orders\RelationManagers\LinesRelationManager;
use App\Filament\App\Resources\Orders\Schemas\OrderForm;
use App\Filament\App\Resources\Orders\Schemas\OrderInfolist;
use App\Filament\App\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $slug = 'orders';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            LinesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\Orders\Pages\ListOrders::route('/'),
            'create' => \App\Filament\App\Resources\Orders\Pages\CreateOrder::route('/create'),
            'edit' => \App\Filament\App\Resources\Orders\Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder<Order>
     */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['service', 'supplier', 'user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['service.name', 'supplier.name', 'user.name'];
    }

    /**
     * @param Order $record
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->service) {
            $details['Service'] = $record->service->name;
        }

        if ($record->supplier) {
            $details['Supplier'] = $record->supplier->name;
        }

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        if ($record->budget) {
            $details['Budget'] = $record->budget->year;
        }

        return $details;
    }
}
