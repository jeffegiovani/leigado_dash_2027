<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Job;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class JobPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any::job');
    }

    public function view(AuthUser $authUser, Job $job): bool
    {
        return $authUser->can('view::job');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create::job');
    }

    public function update(AuthUser $authUser, Job $job): bool
    {
        return $authUser->can('update::job');
    }

    public function delete(AuthUser $authUser, Job $job): bool
    {
        return $authUser->can('delete::job');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any::job');
    }

    public function restore(AuthUser $authUser, Job $job): bool
    {
        return $authUser->can('restore::job');
    }

    public function forceDelete(AuthUser $authUser, Job $job): bool
    {
        return $authUser->can('force_delete::job');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any::job');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any::job');
    }

    public function replicate(AuthUser $authUser, Job $job): bool
    {
        return $authUser->can('replicate::job');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder::job');
    }
}
