<?php

namespace App\Filament\Dash\Resources\Faqs\Pages;

use App\Filament\Dash\Resources\Faqs\FaqResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['slug'] = str($data['slug'] ?? $data['title'])->slug('-', 'pt_BR', ['@' => '-'])->limit(120, '');

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            // \Filament\Actions\ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    // protected function getSavedNotification(): ?Notification
    // {
    //     return Notification::make()
    //         ->success()
    //         ->title('Item atualizado')
    //         ->body('O item foi atualizado com sucesso.');
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
