<?php

namespace App\Filament\Dash\Resources\SiteContacts\Pages;

use App\Filament\Dash\Resources\SiteContacts\SiteContactResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteContact extends EditRecord
{
    protected static string $resource = SiteContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
