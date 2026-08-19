<?php

namespace App\Filament\Dash\Resources\SuccessCaseResource\Pages;

use App\Filament\Dash\Resources\SuccessCaseResource;
use App\Forms\MakeSlugStringTrait;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSuccessCase extends CreateRecord
{
    use MakeSlugStringTrait;

    protected static string $resource = SuccessCaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! blank($data['title']) || ! blank($data['slug'])) {
            $data['slug'] = self::makeSlugString(
                fullTitle: $data['title'],
                modelFullyClassName: self::getModel(),
                slug: $data['slug'] ?? null,
                limitChars: 123,
            );
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // $this->clearSiteCache();
    }

    // protected function getCreatedNotification(): ?Notification
    // {
    //     return Notification::make()
    //         ->success()
    //         ->title('Item Cadastrado')
    //         ->body('Seu item foi cadastrado com sucesso.');
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
