<?php

namespace App\Filament\Dash\Resources\BlogResource\Pages;

use App\Filament\Dash\Resources\BlogResource;
use App\Filament\Dash\Resources\BlogResource\Actions\DeleteTasksTrait;
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
