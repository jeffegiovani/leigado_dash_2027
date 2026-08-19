<?php

namespace App\Filament\Dash\Resources\FaqResource\Pages;

use App\Filament\Dash\Resources\FaqResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqResource::class;

    protected static ?string $navigationLabel = 'Cadastrar Pergunta Frequente'; // Para Menus e para o Spotlight

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = str($data['slug'] ?? $data['title'])->slug('-', 'pt_BR', ['@' => '-'])->limit(120, '');

        return $data;
    }

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
