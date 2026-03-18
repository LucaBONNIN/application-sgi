<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OrderStatusTest extends TestCase
{
    #[Test]
    public function sentCanTransitionToProcessingAndCancelled(): void
    {
        $status = OrderStatus::Sent;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Processing));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Ordered));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Received));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function processingCanTransitionToOrderedAndCancelled(): void
    {
        $status = OrderStatus::Processing;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Ordered));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Received));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function orderedCanTransitionToReceivedAndCancelled(): void
    {
        $status = OrderStatus::Ordered;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Received));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Processing));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function receivedCanTransitionToClosedAndCancelled(): void
    {
        $status = OrderStatus::Received;

        $this->assertTrue($status->canTransitionTo(OrderStatus::Closed));
        $this->assertTrue($status->canTransitionTo(OrderStatus::Cancelled));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Processing));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Ordered));
    }

    #[Test]
    public function closedIsTerminalAndHasNoTransitions(): void
    {
        $status = OrderStatus::Closed;

        $this->assertTrue($status->isTerminal());
        $this->assertEmpty($status->allowedTransitions());
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Cancelled));
    }

    #[Test]
    public function cancelledIsTerminalAndHasNoTransitions(): void
    {
        $status = OrderStatus::Cancelled;

        $this->assertTrue($status->isTerminal());
        $this->assertEmpty($status->allowedTransitions());
        $this->assertFalse($status->canTransitionTo(OrderStatus::Sent));
        $this->assertFalse($status->canTransitionTo(OrderStatus::Closed));
    }

    #[Test]
    public function nonTerminalStatusesAreNotTerminal(): void
    {
        $this->assertFalse(OrderStatus::Sent->isTerminal());
        $this->assertFalse(OrderStatus::Processing->isTerminal());
        $this->assertFalse(OrderStatus::Ordered->isTerminal());
        $this->assertFalse(OrderStatus::Received->isTerminal());
    }
}
