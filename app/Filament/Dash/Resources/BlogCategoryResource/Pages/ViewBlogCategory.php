<?php

namespace App\Filament\Dash\Resources\BlogCategoryResource\Pages;

use App\Filament\Dash\Resources\BlogCategoryResource;
use App\Filament\Dash\Resources\BlogCategoryResource\Actions\DeleteTasksTrait;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBlogCategory extends ViewRecord
{
    use DeleteTasksTrait;

    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            DeleteAction::make()
                ->label('Excluir')
                ->before(function ($record, $action) {
                    self::executeDeleteTasksSingle($record, $action);
                }),

            ForceDeleteAction::make()
                ->before(function ($record, $action) {
                    self::executeDeleteTasksSingle($record, $action);
                }),

            RestoreAction::make(),
        ];
    }
}
