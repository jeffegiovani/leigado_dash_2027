<?php

namespace App\Filament\Dash\Resources\JobResource\Pages;

use App\Filament\Dash\Resources\JobResource;
use App\Forms\MakeSlugStringTrait;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    use MakeSlugStringTrait;

    protected static string $resource = JobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = self::makeSlugString(
            fullTitle: $data['title'],
            modelFullyClassName: self::getModel(),
            slug: $data['slug'] ?? null,
            limitChars: 123,
        );

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
