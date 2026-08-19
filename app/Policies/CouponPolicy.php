<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Coupon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CouponPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any::coupon');
    }

    public function view(AuthUser $authUser, Coupon $coupon): bool
    {
        return $authUser->can('view::coupon');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create::coupon');
    }

    public function update(AuthUser $authUser, Coupon $coupon): bool
    {
        return $authUser->can('update::coupon');
    }

    public function delete(AuthUser $authUser, Coupon $coupon): bool
    {
        return $authUser->can('delete::coupon');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any::coupon');
    }

    public function restore(AuthUser $authUser, Coupon $coupon): bool
    {
        return $authUser->can('restore::coupon');
    }

    public function forceDelete(AuthUser $authUser, Coupon $coupon): bool
    {
        return $authUser->can('force_delete::coupon');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any::coupon');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any::coupon');
    }

    public function replicate(AuthUser $authUser, Coupon $coupon): bool
    {
        return $authUser->can('replicate::coupon');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder::coupon');
    }
}
