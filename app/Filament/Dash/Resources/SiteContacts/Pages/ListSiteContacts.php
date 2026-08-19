<?php

namespace App\Filament\Dash\Resources\SiteContacts\Pages;

use App\Filament\Dash\Resources\SiteContacts\SiteContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiteContacts extends ListRecords
{
    protected static string $resource = SiteContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
