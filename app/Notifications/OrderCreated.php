<?php

namespace App\Notifications;

use App\Filament\App\Resources\Orders\OrderResource;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
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
            ->subject("Nouvelle demande #{$this->order->id}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line('Une nouvelle demande de commande a été créée.')
            ->line("**Demandeur :** {$this->order->user->name}")
            ->line("**Service :** {$this->order->service->name}")
            ->line("**Fournisseur :** {$this->order->supplier->name}")
            ->action('Voir la demande', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'user_name' => $this->order->user->name,
        ];
    }
}
