<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Order');
    }

    public function view(AuthUser $authUser, Order $order): bool
    {
        if (!$authUser->can('View:Order')) {
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
        if (!$authUser->can('Update:Order')) {
            return false;
        }

        if ($authUser->id === $order->user_id) {
            return true;
        }

        return $authUser->can('BypassOwnership:Order');
    }

    public function delete(AuthUser $authUser, Order $order): bool
    {
        if (!$authUser->can('Delete:Order')) {
            return false;
        }

        if ($authUser->id === $order->user_id) {
            return true;
        }

        return $authUser->can('BypassOwnership:Order');
    }

    public function restore(AuthUser $authUser, Order $order): bool
    {
        if (!$authUser->can('Restore:Order')) {
            return false;
        }

        if ($authUser->id === $order->user_id) {
            return true;
        }

        return $authUser->can('BypassOwnership:Order');
    }

    public function forceDelete(AuthUser $authUser, Order $order): bool
    {
        if (!$authUser->can('ForceDelete:Order')) {
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

}
