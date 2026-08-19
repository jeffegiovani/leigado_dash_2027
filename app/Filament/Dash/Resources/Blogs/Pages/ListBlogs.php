<?php

namespace App\Filament\Dash\Resources\Blogs\Pages;

use App\Filament\Dash\Resources\Blogs\Actions\DeleteTasksTrait;
use App\Filament\Dash\Resources\Blogs\BlogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogs extends ListRecords
{
    use DeleteTasksTrait;

    protected static string $resource = BlogResource::class;

    protected static ?string $navigationLabel = 'Listar Artigos'; // Para Menus e para o Spotlight

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
