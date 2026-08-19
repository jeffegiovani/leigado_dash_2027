<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FaqGroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FaqGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any::faq_group');
    }

    public function view(AuthUser $authUser, FaqGroup $faqGroup): bool
    {
        return $authUser->can('view::faq_group');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create::faq_group');
    }

    public function update(AuthUser $authUser, FaqGroup $faqGroup): bool
    {
        return $authUser->can('update::faq_group');
    }

    public function delete(AuthUser $authUser, FaqGroup $faqGroup): bool
    {
        return $authUser->can('delete::faq_group');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any::faq_group');
    }

    public function restore(AuthUser $authUser, FaqGroup $faqGroup): bool
    {
        return $authUser->can('restore::faq_group');
    }

    public function forceDelete(AuthUser $authUser, FaqGroup $faqGroup): bool
    {
        return $authUser->can('force_delete::faq_group');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any::faq_group');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any::faq_group');
    }

    public function replicate(AuthUser $authUser, FaqGroup $faqGroup): bool
    {
        return $authUser->can('replicate::faq_group');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder::faq_group');
    }
}
