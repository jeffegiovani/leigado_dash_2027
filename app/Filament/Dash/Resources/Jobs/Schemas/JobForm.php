<?php

namespace App\Filament\Dash\Resources\Jobs\Schemas;

use App\Enums\ResourceVisibilityEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class JobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->columnSpan([
                        // 'md' => 2,
                        // '2xl' => 1,
                    ])
                    ->columns(1)
                    ->schema([
                        Forms\Components\ToggleButtons::make('visibility')
                            ->options(ResourceVisibilityEnum::class)
                            ->hiddenLabel()
                            ->required()
                            ->default(ResourceVisibilityEnum::Public)
                            // ->grouped()
                            ->inline()
                            // ->hint(fn() => view('forms.resource-visibility-hint-helper'))
                            ->label('Visibilidade'),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(120)
                            ->columnSpanFull()
                            ->label('Titulo da Vaga'),

                        Forms\Components\TextInput::make('slug')
                            // ->required()
                            // ->unique(ignoreRecord: true)
                            ->maxLength(125)
                            // ->visibleOn('edit')
                            ->columnSpanFull()
                            ->hint(new HtmlString('<small>Deixe vazio para gerar automaticamente</small>'))
                            ->label('Url de Acesso'),

                        Schemas\Components\Group::make()
                            ->columnSpan([
                                // 'md' => 2,
                                // '2xl' => 1,
                            ])
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('location')
                                    ->required()
                                    ->maxLength(40)
                                    ->placeholder('Dois Vizinhos, PR / Remoto / Externo')
                                    ->label('Localização'),

                                Forms\Components\Select::make('author_id')
                                    ->relationship(
                                        name: 'author',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: function (Builder $query): Builder {
                                            if (auth()->user()->id != 1) {
                                                $query->where('id', '!=', 1);
                                            }

                                            return $query;
                                        }
                                    )
                                    ->default(auth()->id())
                                    ->required()
                                    ->label('Responsável pela Vaga'),
                            ]),

                    ]),

                Schemas\Components\Group::make()
                    ->columnSpan([
                        // 'md' => 2,
                        // '2xl' => 1,
                    ])
                    ->columns(1)
                    ->schema([
                        Forms\Components\MarkdownEditor::make('content')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'heading',
                                // 'link',
                                'table',
                            ])
                            ->columnSpanFull()
                            ->label('Detalhamento da Vaga'),
                    ]),
            ]);
    }
}
