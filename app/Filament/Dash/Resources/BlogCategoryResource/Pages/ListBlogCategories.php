<?php

namespace App\Filament\Dash\Resources\BlogCategoryResource\Pages;

use App\Filament\Dash\Resources\BlogCategoryResource;
use App\Filament\Dash\Resources\BlogCategoryResource\Actions\DeleteTasksTrait;
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
