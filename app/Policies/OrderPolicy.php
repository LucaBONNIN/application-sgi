<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Order');
    }

    public function view(AuthUser $authUser, Order $order): bool
    {
        if (! $authUser->can('View:Order')) {
            return false;
        }

        if ($authUser->id === $order->user_id) {
            return true;
        }

        return $authUser->can('BypassOwnership:Order');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Order');
    }

    public function update(AuthUser $authUser, Order $order): bool
    {
        if (! $authUser->can('Update:Order')) {
            return false;
        }

        if ($authUser->can('BypassOwnership:Order')) {
            return true;
        }

        // Demandeurs can only edit their own orders while in Sent status
        return $authUser->id === $order->user_id
            && $order->status === OrderStatus::Sent;
    }

    public function delete(AuthUser $authUser, Order $order): bool
    {
        if (! $authUser->can('Delete:Order')) {
            return false;
        }

        if ($authUser->id === $order->user_id) {
            return true;
        }

        return $authUser->can('BypassOwnership:Order');
    }

    public function restore(AuthUser $authUser, Order $order): bool
    {
        if (! $authUser->can('Restore:Order')) {
            return false;
        }

        if ($authUser->id === $order->user_id) {
            return true;
        }

        return $authUser->can('BypassOwnership:Order');
    }

    public function forceDelete(AuthUser $authUser, Order $order): bool
    {
        if (! $authUser->can('ForceDelete:Order')) {
            return false;
        }

        if ($authUser->id === $order->user_id) {
            return true;
        }

        return $authUser->can('BypassOwnership:Order');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Order');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Order');
    }

    public function replicate(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('Replicate:Order');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Order');
    }

    /**
     * Determine if the user can cancel the order.
     */
    public function cancel(AuthUser $authUser, Order $order): bool
    {
        if ($order->status->isTerminal()) {
            return false;
        }

        // Admin can cancel any non-terminal order
        if ($authUser->can('BypassOwnership:Order')) {
            return true;
        }

        // Demandeur can only cancel their own orders in Sent status
        return $authUser->id === $order->user_id
            && $order->status === OrderStatus::Sent;
    }

    /**
     * Determine if the user can transition the order to Processing.
     */
    public function processOrder(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('BypassOwnership:Order')
            && $order->status->canTransitionTo(OrderStatus::Processing);
    }

    /**
     * Determine if the user can transition the order to Ordered.
     */
    public function markOrdered(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('BypassOwnership:Order')
            && $order->status->canTransitionTo(OrderStatus::Ordered);
    }

    /**
     * Determine if the user can transition the order to Received.
     */
    public function markReceived(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('BypassOwnership:Order')
            && $order->status->canTransitionTo(OrderStatus::Received);
    }

    /**
     * Determine if the user can transition the order to Closed.
     */
    public function closeOrder(AuthUser $authUser, Order $order): bool
    {
        return $authUser->can('BypassOwnership:Order')
            && $order->status->canTransitionTo(OrderStatus::Closed);
    }
}
