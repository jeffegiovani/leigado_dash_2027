<?php

namespace App\Filament\Dash\Resources\SiteContacts\Schemas;

use App\Enums\ContactChannelEnum;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;

class SiteContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('Detalhes')
                    ->description('Detalhes do contato recebido nos canais do site')
                    ->aside()
                    ->columns([
                        'md' => 2,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(80)
                            ->default(null)
                            ->label('Nome'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(80)
                            ->label('Email'),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->default(null)
                            ->label('Fone'),

                        Forms\Components\Select::make('channel')
                            ->required()
                            ->options(ContactChannelEnum::class)
                            ->searchable()
                            ->preload()
                            ->label('Canal de Contato'),

                        Forms\Components\Textarea::make('content')
                            ->disabled()
                            ->rows(5)
                            ->columnSpanFull()
                            ->label('Detalhes do Payload'),

                        // Forms\Components\Textarea::make('content')
                        //     ->columnSpanFull(),
                    ]),
            ]);
    }
}
