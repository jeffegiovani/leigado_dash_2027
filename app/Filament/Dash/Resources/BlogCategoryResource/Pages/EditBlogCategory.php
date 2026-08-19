<?php

namespace App\Filament\Dash\Resources\BlogCategoryResource\Pages;

use App\Filament\Dash\Resources\BlogCategoryResource;
use App\Filament\Dash\Resources\BlogCategoryResource\Actions\DeleteTasksTrait;
use App\Forms\MakeSlugStringTrait;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBlogCategory extends EditRecord
{
    use DeleteTasksTrait;
    use MakeSlugStringTrait;

    protected static string $resource = BlogCategoryResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['slug'] = self::makeSlugString(
            fullTitle: $data['title'],
            modelFullyClassName: self::getModel(),
            slug: $data['slug'],
            limitChars: 123,
            currentRecordId: $this->record->id,
        );

        return $data;
    }

    protected function afterSave(): void
    {
        // $this->clearSiteCache();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),

            DeleteAction::make()
                ->before(function ($record, $action) {
                    self::executeDeleteTasksSingle($record, $action);
                }),
        ];
    }

    // protected function getSavedNotification(): ?Notification
    // {
    //     return Notification::make()
    //         ->success()
    //         ->title('Item atualizado')
    //         ->body('O item foi atualizado com sucesso.');
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
