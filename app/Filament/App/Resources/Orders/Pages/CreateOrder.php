<?php

namespace App\Filament\App\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\App\Resources\Orders\OrderResource;
use App\Models\User;
use App\Notifications\OrderCreated;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()->can('BypassOwnership:Order')) {
            $data['user_id'] = auth()->id();
        }

        $data['status'] ??= OrderStatus::Sent->value;

        return $data;
    }

    protected function afterCreate(): void
    {
        $order = $this->record;
        $order->load(['user', 'service', 'supplier']);

        // Notify admins
        $admins = User::role('super_admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new OrderCreated($order));
        }
    }
}
