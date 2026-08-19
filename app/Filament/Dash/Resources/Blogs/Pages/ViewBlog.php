<?php

namespace App\Filament\Dash\Resources\Blogs\Pages;

use App\Filament\Dash\Resources\Blogs\Actions\DeleteTasksTrait;
use App\Filament\Dash\Resources\Blogs\BlogResource;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBlog extends ViewRecord
{
    use DeleteTasksTrait;

    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            // \Filament\Actions\DeleteAction::make()
            //     ->label('Excluir'),

            // \Filament\Actions\ForceDeleteAction::make()
            //     ->before(function ($record, $action) {
            //         self::executeDeleteTasksSingle($record, $action);
            //     }),

            RestoreAction::make(),
        ];
    }
}
