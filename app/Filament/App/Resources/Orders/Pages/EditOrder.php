<?php

namespace App\Filament\App\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\App\Resources\Orders\OrderResource;
use App\Models\Budget;
use App\Notifications\OrderStatusChanged;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('processOrder')
                ->label(__('filament/resources/order.actions.process'))
                ->color('warning')
                ->icon(Heroicon::Eye)
                ->requiresConfirmation()
                ->modalDescription(__('filament/resources/order.actions.process_description'))
                ->schema([
                    Select::make('budget_id')
                        ->label(__('filament/resources/order.fields.budget'))
                        ->options(fn () => Budget::query()
                            ->where('service_id', $this->record->service_id)
                            ->get()
                            ->pluck('budget_name', 'id'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $previousStatus = $this->record->status;

                    $this->record->update([
                        'status' => OrderStatus::Processing,
                        'budget_id' => $data['budget_id'],
                    ]);

                    $this->refreshFormData(['status', 'budget_id']);

                    $this->record->user->notify(
                        new OrderStatusChanged($this->record, $previousStatus)
                    );
                })
                ->visible(fn (): bool => auth()->user()->can('processOrder', $this->record)),

            Action::make('markOrdered')
                ->label(__('filament/resources/order.actions.mark_ordered'))
                ->color('success')
                ->icon(Heroicon::Check)
                ->requiresConfirmation()
                ->modalDescription(__('filament/resources/order.actions.mark_ordered_description'))
                ->schema([
                    DatePicker::make('estimated_delivery_date')
                        ->label(__('filament/resources/order.fields.estimated_delivery_date'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $previousStatus = $this->record->status;

                    $this->record->update([
                        'status' => OrderStatus::Ordered,
                        'estimated_delivery_date' => $data['estimated_delivery_date'],
                    ]);

                    $this->refreshFormData(['status', 'estimated_delivery_date']);

                    $this->record->user->notify(
                        new OrderStatusChanged($this->record, $previousStatus)
                    );
                })
                ->visible(fn (): bool => auth()->user()->can('markOrdered', $this->record)),

            Action::make('markReceived')
                ->label(__('filament/resources/order.actions.mark_received'))
                ->color('primary')
                ->icon(Heroicon::Check)
                ->requiresConfirmation()
                ->modalDescription(__('filament/resources/order.actions.mark_received_description'))
                ->action(function (): void {
                    $previousStatus = $this->record->status;

                    $this->record->update(['status' => OrderStatus::Received]);

                    $this->refreshFormData(['status']);

                    $this->record->user->notify(
                        new OrderStatusChanged($this->record, $previousStatus)
                    );
                })
                ->visible(fn (): bool => auth()->user()->can('markReceived', $this->record)),

            Action::make('closeOrder')
                ->label(__('filament/resources/order.actions.close'))
                ->color('info')
                ->icon(Heroicon::CheckCircle)
                ->requiresConfirmation()
                ->modalDescription(__('filament/resources/order.actions.close_description'))
                ->action(function (): void {
                    $previousStatus = $this->record->status;

                    $this->record->update(['status' => OrderStatus::Closed]);

                    $this->refreshFormData(['status']);

                    $this->record->user->notify(
                        new OrderStatusChanged($this->record, $previousStatus)
                    );
                })
                ->visible(fn (): bool => auth()->user()->can('closeOrder', $this->record)),

            Action::make('cancelOrder')
                ->label(__('filament/resources/order.actions.cancel'))
                ->color('danger')
                ->icon(Heroicon::XMark)
                ->requiresConfirmation()
                ->modalDescription(__('filament/resources/order.actions.cancel_description'))
                ->action(function (): void {
                    $previousStatus = $this->record->status;

                    $this->record->update(['status' => OrderStatus::Cancelled]);

                    $this->refreshFormData(['status']);

                    $this->record->user->notify(
                        new OrderStatusChanged($this->record, $previousStatus)
                    );
                })
                ->visible(fn (): bool => auth()->user()->can('cancel', $this->record)),

            DeleteAction::make()
                ->visible(fn (): bool => auth()->user()->can('delete', $this->record)),
        ];
    }
}
