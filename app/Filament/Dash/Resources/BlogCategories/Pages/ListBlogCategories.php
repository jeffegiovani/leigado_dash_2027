<?php

namespace App\Filament\Dash\Resources\BlogCategories\Pages;

use App\Filament\Dash\Resources\BlogCategories\Actions\DeleteTasksTrait;
use App\Filament\Dash\Resources\BlogCategories\BlogCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogCategories extends ListRecords
{
    use DeleteTasksTrait;

    protected static string $resource = BlogCategoryResource::class;

    protected static ?string $navigationLabel = 'Listar Categorias do Blog'; // Para Menus e para o Spotlight

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
