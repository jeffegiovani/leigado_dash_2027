<?php

namespace App\Filament\Dash\Resources\FaqResource\Pages;

use App\Filament\Dash\Resources\FaqResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFaq extends ViewRecord
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
