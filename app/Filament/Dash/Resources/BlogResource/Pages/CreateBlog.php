<?php

namespace App\Filament\Dash\Resources\BlogResource\Pages;

use App\Filament\Dash\Resources\BlogResource;
use App\Forms\MakeSlugStringTrait;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    use MakeSlugStringTrait;

    protected static string $resource = BlogResource::class;

    protected static ?string $navigationLabel = 'Cadastrar Artigo'; // Para Menus e para o Spotlight

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = self::makeSlugString(
            fullTitle: $data['title'],
            modelFullyClassName: self::getModel(),
            slug: $data['slug'] ?? null,
            limitChars: 123,
        );

        $data['content'] = str($data['content'])->replace('src="/storage/', 'src="https://site-adm.leigado.com.br/storage/');

        return $data;
    }

    protected function afterCreate(): void
    {
        // $this->clearSiteCache();
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
