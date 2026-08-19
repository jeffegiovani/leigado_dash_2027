<?php

namespace App\Filament\Dash\Resources\SuccessCaseResource\Pages;

use App\Filament\Dash\Resources\SuccessCaseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSuccessCase extends ViewRecord
{
    protected static string $resource = SuccessCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
