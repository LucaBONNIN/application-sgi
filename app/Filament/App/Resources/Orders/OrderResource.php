<?php

namespace App\Filament\App\Resources\Orders;

use App\Filament\App\Resources\Orders\Pages\CreateOrder;
use App\Filament\App\Resources\Orders\Pages\EditOrder;
use App\Filament\App\Resources\Orders\Pages\ListOrders;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::InboxArrowDown;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.orders');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/order.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/order.label');
    }

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
            LinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
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
     * @param  Order  $record
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // If the user DOES NOT have the bypass permission,
        // restrict the query to only their orders.
        if (! auth()->user()->can('BypassOwnership:Order')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
