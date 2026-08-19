<?php

namespace App\Filament\Dash\Resources\FaqGroupResource\Pages;

use App\Filament\Dash\Resources\FaqGroupResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFaqGroup extends ViewRecord
{
    protected static string $resource = FaqGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
