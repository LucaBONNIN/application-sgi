<?php

namespace App\Notifications;

use App\Enums\OrderStatus;
use App\Filament\App\Resources\Orders\OrderResource;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public OrderStatus $previousStatus,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = OrderResource::getUrl('edit', ['record' => $this->order]);

        return (new MailMessage)
            ->subject("Demande #{$this->order->id} - Changement de statut")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le statut de votre demande #{$this->order->id} a changé.")
            ->line("**{$this->previousStatus->getLabel()}** → **{$this->order->status->getLabel()}**")
            ->line("Fournisseur : {$this->order->supplier->name}")
            ->line("Service : {$this->order->service->name}")
            ->action('Voir la demande', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'previous_status' => $this->previousStatus->value,
            'new_status' => $this->order->status->value,
        ];
    }
}
