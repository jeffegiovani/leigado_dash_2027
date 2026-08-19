<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SuccessCase;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SuccessCasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any::success_case');
    }

    public function view(AuthUser $authUser, SuccessCase $successCase): bool
    {
        return $authUser->can('view::success_case');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create::success_case');
    }

    public function update(AuthUser $authUser, SuccessCase $successCase): bool
    {
        return $authUser->can('update::success_case');
    }

    public function delete(AuthUser $authUser, SuccessCase $successCase): bool
    {
        return $authUser->can('delete::success_case');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any::success_case');
    }

    public function restore(AuthUser $authUser, SuccessCase $successCase): bool
    {
        return $authUser->can('restore::success_case');
    }

    public function forceDelete(AuthUser $authUser, SuccessCase $successCase): bool
    {
        return $authUser->can('force_delete::success_case');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any::success_case');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any::success_case');
    }

    public function replicate(AuthUser $authUser, SuccessCase $successCase): bool
    {
        return $authUser->can('replicate::success_case');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder::success_case');
    }
}
