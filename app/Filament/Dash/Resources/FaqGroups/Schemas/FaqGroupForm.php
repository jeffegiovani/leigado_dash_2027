<?php

namespace App\Filament\Dash\Resources\FaqGroups\Schemas;

use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class FaqGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                // 'md' => 2,
            ])
            ->schema([
                Schemas\Components\Section::make('Agrupamento de FAQs')
                    ->description('Crie grupos de FAQ que serão exibidas nos respectivos produtos dentro do site')
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(80)
                            ->label('Título'),

                        Forms\Components\TextInput::make('slug')
                            ->maxLength(125)
                            ->default(null)
                            ->hint(new HtmlString('<small>Deixe vazio pra gerar automaticamente</small>'))
                            ->label('URL de Acesso'),
                    ]),
            ]);
    }
}
