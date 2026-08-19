<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SiteContact;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SiteContactPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any::site_contact');
    }

    public function view(AuthUser $authUser, SiteContact $siteContact): bool
    {
        return $authUser->can('view::site_contact');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create::site_contact');
    }

    public function update(AuthUser $authUser, SiteContact $siteContact): bool
    {
        return $authUser->can('update::site_contact');
    }

    public function delete(AuthUser $authUser, SiteContact $siteContact): bool
    {
        return $authUser->can('delete::site_contact');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any::site_contact');
    }

    public function restore(AuthUser $authUser, SiteContact $siteContact): bool
    {
        return $authUser->can('restore::site_contact');
    }

    public function forceDelete(AuthUser $authUser, SiteContact $siteContact): bool
    {
        return $authUser->can('force_delete::site_contact');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any::site_contact');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any::site_contact');
    }

    public function replicate(AuthUser $authUser, SiteContact $siteContact): bool
    {
        return $authUser->can('replicate::site_contact');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder::site_contact');
    }
}
