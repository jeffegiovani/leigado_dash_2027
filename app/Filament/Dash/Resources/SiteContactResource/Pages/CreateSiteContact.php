<?php

namespace App\Filament\Dash\Resources\SiteContactResource\Pages;

use App\Filament\Dash\Resources\SiteContactResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteContact extends CreateRecord
{
    protected static string $resource = SiteContactResource::class;

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
