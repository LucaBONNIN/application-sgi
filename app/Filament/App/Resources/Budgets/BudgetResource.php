<?php

namespace App\Filament\App\Resources\Budgets;

use App\Filament\App\Resources\Budgets\Pages\CreateBudget;
use App\Filament\App\Resources\Budgets\Pages\EditBudget;
use App\Filament\App\Resources\Budgets\Pages\ListBudgets;
use App\Filament\App\Resources\Budgets\Schemas\BudgetForm;
use App\Filament\App\Resources\Budgets\Tables\BudgetsTable;
use App\Models\Budget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Banknotes;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.orders');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/budget.navigation_label');
    }

    protected static ?string $recordTitleAttribute = 'budgetName';

    public static function form(Schema $schema): Schema
    {
        return BudgetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BudgetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBudgets::route('/'),
            'create' => CreateBudget::route('/create'),
            'edit' => EditBudget::route('/{record}/edit'),
        ];
    }
}
