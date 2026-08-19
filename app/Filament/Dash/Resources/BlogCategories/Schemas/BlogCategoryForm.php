<?php

namespace App\Filament\Dash\Resources\BlogCategories\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->columnSpanFull()
                    ->columns([
                        'md' => 2,
                    ])
                    ->schema([
                        Schemas\Components\Section::make('Categorias')
                            ->description('Crie categorias para agrupar os assuntos do Blog')
                            ->aside()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(120)
                                    ->label('Nome da Categoria'),

                                Forms\Components\TextInput::make('slug')
                                    // ->required()
                                    // ->visibleOn('edit')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(125)
                                    ->hint(new HtmlString('<small>Deixe vazio para gerar automaticamente</small>'))
                                    ->validationMessages([
                                        'unique' => 'Você já usa essa URL para outra categoria',
                                    ])
                                    ->label('URL de Acesso'),

                            ]),
                    ]),
            ]);
    }
}
