<?php

namespace App\Filament\Dash\Resources\BlogResource\Actions;

use App\Models\Blog;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

trait DeleteTasksTrait
{
    public static function executeDeleteTasksSingle(
        Blog $record,
        DeleteAction|ForceDeleteAction $action
    ) {
        self::deleteImages(images: [
            $record->cover,
            $record->thumb,
        ]);
    }

    public static function executeDeleteTasksBulk(
        Collection $records,
        DeleteBulkAction|ForceDeleteBulkAction $action
    ) {
        foreach ($records as $record) {
            self::deleteImages(images: [
                $record->cover,
                $record->thumb,
            ]);
        }
    }

    protected static function deleteImages(?array $images = [])
    {
        if (is_array($images)) {
            foreach ($images as $image) {
                Storage::disk('public');
                // ->delete(tenant_storage_path('blog_images') . $image)
            }
        }
    }
}
