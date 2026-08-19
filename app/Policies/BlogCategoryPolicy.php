<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BlogCategory;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class BlogCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any::blog_category');
    }

    public function view(AuthUser $authUser, BlogCategory $blogCategory): bool
    {
        return $authUser->can('view::blog_category');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create::blog_category');
    }

    public function update(AuthUser $authUser, BlogCategory $blogCategory): bool
    {
        return $authUser->can('update::blog_category');
    }

    public function delete(AuthUser $authUser, BlogCategory $blogCategory): bool
    {
        return $authUser->can('delete::blog_category');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any::blog_category');
    }

    public function restore(AuthUser $authUser, BlogCategory $blogCategory): bool
    {
        return $authUser->can('restore::blog_category');
    }

    public function forceDelete(AuthUser $authUser, BlogCategory $blogCategory): bool
    {
        return $authUser->can('force_delete::blog_category');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any::blog_category');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any::blog_category');
    }

    public function replicate(AuthUser $authUser, BlogCategory $blogCategory): bool
    {
        return $authUser->can('replicate::blog_category');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder::blog_category');
    }
}
