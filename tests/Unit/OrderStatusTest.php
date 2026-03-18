<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OrderStatusTest extends TestCase
{
    #[Test]
    public function sent_can_transition_to_processing_and_cancelled(): void
    {
        $status = OrderStatus::Sent;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Processing));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Ordered));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Received));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function processing_can_transition_to_ordered_and_cancelled(): void
    {
        $status = OrderStatus::Processing;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Ordered));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Received));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function ordered_can_transition_to_received_and_cancelled(): void
    {
        $status = OrderStatus::Ordered;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Received));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Processing));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function received_can_transition_to_closed_and_cancelled(): void
    {
        $status = OrderStatus::Received;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Closed));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Processing));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Ordered));
    }

    #[Test]
    public function closed_is_terminal_and_has_no_transitions(): void
    {
        $status = OrderStatus::Closed;

        $this->assertTrue($status->isTerminal());
        $this->assertEmpty($status->allowedTransitions());
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Cancelled));
    }

    #[Test]
    public function cancelled_is_terminal_and_has_no_transitions(): void
    {
        $status = OrderStatus::Cancelled;

        $this->assertTrue($status->isTerminal());
        $this->assertEmpty($status->allowedTransitions());
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function non_terminal_statuses_are_not_terminal(): void
    {
        $this->assertFalse(OrderStatus::Sent->isTerminal());
        $this->assertFalse(OrderStatus::Processing->isTerminal());
        $this->assertFalse(OrderStatus::Ordered->isTerminal());
        $this->assertFalse(OrderStatus::Received->isTerminal());
    }
}
