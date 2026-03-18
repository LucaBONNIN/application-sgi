<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\VacationPeriod;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class VacationPeriodPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VacationPeriod');
    }

    public function view(AuthUser $authUser, VacationPeriod $vacationPeriod): bool
    {
        return $authUser->can('View:VacationPeriod');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VacationPeriod');
    }

    public function update(AuthUser $authUser, VacationPeriod $vacationPeriod): bool
    {
        return $authUser->can('Update:VacationPeriod');
    }

    public function delete(AuthUser $authUser, VacationPeriod $vacationPeriod): bool
    {
        return $authUser->can('Delete:VacationPeriod');
    }

    public function restore(AuthUser $authUser, VacationPeriod $vacationPeriod): bool
    {
        return $authUser->can('Restore:VacationPeriod');
    }

    public function forceDelete(AuthUser $authUser, VacationPeriod $vacationPeriod): bool
    {
        return $authUser->can('ForceDelete:VacationPeriod');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VacationPeriod');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VacationPeriod');
    }

    public function replicate(AuthUser $authUser, VacationPeriod $vacationPeriod): bool
    {
        return $authUser->can('Replicate:VacationPeriod');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VacationPeriod');
    }
}
