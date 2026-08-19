<?php

namespace App\Filament\Dash\Resources\FaqGroupResource\Pages;

use App\Filament\Dash\Resources\FaqGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFaqGroups extends ListRecords
{
    protected static string $resource = FaqGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
