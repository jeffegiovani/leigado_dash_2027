<?php

namespace App\Filament\Dash\Resources\FaqGroupResource\Pages;

use App\Filament\Dash\Resources\FaqGroupResource;
use App\Filament\Dash\Resources\FaqGroupResource\Actions\DeleteTasksTrait;
use App\Forms\MakeSlugStringTrait;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFaqGroup extends EditRecord
{
    use DeleteTasksTrait;
    use MakeSlugStringTrait;

    protected static string $resource = FaqGroupResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['slug'] = self::makeSlugString(
            fullTitle: $data['title'],
            modelFullyClassName: self::getModel(),
            slug: $data['slug'] ?? null,
            limitChars: 123,
        );

        return $data;
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
