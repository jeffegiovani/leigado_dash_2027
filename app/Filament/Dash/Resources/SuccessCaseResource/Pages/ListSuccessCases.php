<?php

namespace App\Filament\Dash\Resources\SuccessCaseResource\Pages;

use App\Filament\Dash\Resources\SuccessCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuccessCases extends ListRecords
{
    protected static string $resource = SuccessCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
