<?php

namespace App\Filament\Dash\Resources\SiteContacts\Pages;

use App\Filament\Dash\Resources\SiteContacts\SiteContactResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSiteContact extends ViewRecord
{
    protected static string $resource = SiteContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
