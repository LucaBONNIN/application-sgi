<?php

namespace Tests\Feature\Notifications;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCreated;
use App\Notifications\OrderStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function order_created_notification_contains_correct_content(): void
    {
        $order = Order::factory()->sent()->create();
        $order->load(['user', 'service', 'supplier']);

        $admin = User::factory()->create();

        $notification = new OrderCreated($order);

        $mail = $notification->toMail($admin);

        $this->assertInstanceOf(MailMessage::class, $mail);
        $this->assertStringContainsString((string) $order->id, $mail->subject);
        $this->assertStringContainsString($admin->name, $mail->greeting);
    }

    #[Test]
    public function order_created_notification_uses_mail_channel(): void
    {
        $order = Order::factory()->sent()->create();
        $order->load(['user', 'service', 'supplier']);

        $notification = new OrderCreated($order);

        $this->assertContains('mail', $notification->via($order->user));
    }

    #[Test]
    public function order_created_to_array_contains_order_id(): void
    {
        $order = Order::factory()->sent()->create();
        $order->load(['user', 'service', 'supplier']);

        $notification = new OrderCreated($order);
        $data = $notification->toArray($order->user);

        $this->assertArrayHasKey('order_id', $data);
        $this->assertSame($order->id, $data['order_id']);
    }

    #[Test]
    public function order_status_changed_notification_contains_correct_content(): void
    {
        $order = Order::factory()->processing()->create();
        $order->load(['user', 'service', 'supplier']);

        $notification = new OrderStatusChanged($order, OrderStatus::Sent);

        $mail = $notification->toMail($order->user);

        $this->assertInstanceOf(MailMessage::class, $mail);
        $this->assertStringContainsString((string) $order->id, $mail->subject);
        $this->assertStringContainsString($order->user->name, $mail->greeting);
    }

    #[Test]
    public function order_status_changed_notification_uses_mail_channel(): void
    {
        $order = Order::factory()->processing()->create();
        $order->load(['user', 'service', 'supplier']);

        $notification = new OrderStatusChanged($order, OrderStatus::Sent);

        $this->assertContains('mail', $notification->via($order->user));
    }

    #[Test]
    public function order_status_changed_to_array_contains_status_info(): void
    {
        $order = Order::factory()->processing()->create();
        $order->load(['user', 'service', 'supplier']);

        $previousStatus = OrderStatus::Sent;
        $notification = new OrderStatusChanged($order, $previousStatus);
        $data = $notification->toArray($order->user);

        $this->assertArrayHasKey('order_id', $data);
        $this->assertArrayHasKey('previous_status', $data);
        $this->assertArrayHasKey('new_status', $data);
        $this->assertSame($order->id, $data['order_id']);
        $this->assertSame($previousStatus->value, $data['previous_status']);
        $this->assertSame($order->status->value, $data['new_status']);
    }
}
