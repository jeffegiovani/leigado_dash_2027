<?php

namespace App\Filament\Dash\Resources\BlogCategories\Pages;

use App\Filament\Dash\Resources\BlogCategories\BlogCategoryResource;
use App\Forms\MakeSlugStringTrait;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogCategory extends CreateRecord
{
    use MakeSlugStringTrait;

    protected static string $resource = BlogCategoryResource::class;

    protected static ?string $navigationLabel = 'Cadastrar Categoria'; // Para Menus e para o Spotlight

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = self::makeSlugString(
            fullTitle: $data['title'],
            modelFullyClassName: self::getModel(),
            slug: $data['slug'],
            limitChars: 123,
        );

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
