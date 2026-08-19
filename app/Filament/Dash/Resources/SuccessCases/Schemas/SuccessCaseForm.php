<?php

namespace App\Filament\Dash\Resources\SuccessCases\Schemas;

use App\Enums\ResourceVisibilityEnum;
use App\Enums\SegmentsEnum;
use App\Filament\Forms\Components\WebpImageUpload;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class SuccessCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            // No Filament 5 a raiz do schema é multi-coluna; o layout original
            // do Filament 3 empilhava as seções, então fixamos em 1 coluna.
            ->columns(1)
            ->components([
                Schemas\Components\Grid::make()
                    // ->aside()
                    ->columns([
                        'sm' => 2,
                    ])
                    ->schema([
                        Schemas\Components\Group::make()
                            ->schema([
                                Schemas\Components\Section::make('Configurações')
                                    ->columnSpan([
                                        'default' => 1,
                                    ])
                                    // ->heading(null)
                                    // ->hiddenLabel()
                                    ->schema([
                                        Forms\Components\ToggleButtons::make('visibility')
                                            ->options(ResourceVisibilityEnum::class)
                                            // ->inlineLabel()
                                            ->hiddenLabel()
                                            ->required()
                                            ->default(ResourceVisibilityEnum::Public)
                                            ->inline()
                                            // ->hint(fn() => view('forms.resource-visibility-hint-helper'))
                                            ->columnSpanFull()
                                            // ->grouped()
                                            ->label('Visibilidade'),

                                        Forms\Components\CheckboxList::make('segments')
                                            ->required()
                                            ->options(SegmentsEnum::class)
                                            // ->columnSpanFull()
                                            ->bulkToggleable()
                                            ->columns([
                                                'sm' => 2,
                                                '2xl' => 4,
                                            ])
                                            ->label('Segmentos'),
                                    ]),
                            ]),

                        Schemas\Components\Group::make()
                            ->schema([
                                Schemas\Components\Section::make('Cliente e Logotipo')
                                    // ->aside()
                                    ->columns([
                                        'md' => 3,
                                    ])
                                    ->schema([
                                        WebpImageUpload::make('logotype')
                                            // ->required(fn($operation) => $operation === 'create')
                                            // ->requiredWith([
                                            //     'customer_name',
                                            //     'customer_location'
                                            // ])

                                            ->required(
                                                fn (Get $get) => ! blank($get('customer_name'))
                                                    || ! blank($get('customer_location'))
                                            )
                                            // ->columnSpanFull()
                                            ->directory('cases/logotypes')
                                            ->imageEditor()
                                            ->imageEditorMode(2)
                                            // ->imageEditorEmptyFillColor('#000000')
                                            ->imageResizeMode('contain')
//                                            ->imageCropAspectRatio('1:1')
                                            ->imageEditorViewportWidth(160)
//                                            ->imageEditorViewportHeight(160)
                                            ->imageResizeTargetWidth('160')
//                                            ->imageResizeTargetHeight('160')
                                            ->imageEditorAspectRatios([null, '1:1'])
                                            ->optimize('webp')
                                            ->helperText(new HtmlString('<small>Min. 160px de largura</small>'))
                                            ->label('Logotipo do Cliente'),

                                        Schemas\Components\Group::make()
                                            ->columnSpan([
                                                'md' => 2,
                                            ])
                                            ->schema([
                                                Forms\Components\TextInput::make('customer_name')
                                                    ->requiredWith([
                                                        'logotype',
                                                    ])
                                                    ->maxLength(120)
                                                    ->label('Nome do Cliente'),

                                                Forms\Components\TextInput::make('customer_location')
                                                    ->maxLength(120)
                                                    ->default(null)
                                                    ->label('Região/Localiz. do Cliente'),
                                            ]),
                                    ]),
                            ]),
                    ]),

                Schemas\Components\Section::make('Depoimento')
                    // ->aside()
                    ->columns([
                        'md' => 3,
                        'lg' => 5,
                    ])
                    ->schema([
                        Schemas\Components\Group::make()
                            ->schema([
                                WebpImageUpload::make('avatar')
                                    // ->requiredWith([
                                    //     'name',
                                    //     'job_position',
                                    //     'testimony',
                                    // ])
                                    ->required(
                                        fn (Get $get) => ! blank($get('name'))
                                            || ! blank($get('job_position'))
                                            || ! blank($get('testimony'))
                                    )
                                    ->avatar()
                                    ->directory('cases/avatars')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->imageResizeMode('cover')
                                    // ->imageCropAspectRatio('1:1')
                                    // ->imageEditorViewportWidth(1280)
                                    // ->imageEditorViewportHeight(720)
                                    ->imageResizeTargetWidth('160')
                                    ->imageResizeTargetHeight('160')
                                    ->imageEditorAspectRatios([null, '1:1'])
                                    ->optimize('webp')
                                    ->helperText(new HtmlString('<small>Min. 160px de largura</small>'))
                                    ->label('Avatar/Perfil'),
                            ]),
                        Schemas\Components\Group::make()
                            ->columnSpan([
                                'md' => 2,
                                'lg' => 4,
                            ])
                            ->schema([
                                Schemas\Components\Grid::make()
                                    ->columns([
                                        'sm' => 2,
                                    ])
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->requiredWith([
                                                'avatar',
                                                'job_position',
                                                'testimony',
                                            ])
                                            ->maxLength(250)
                                            ->label('Nome do Depoente'),

                                        Forms\Components\TextInput::make('job_position')
                                            ->maxLength(190)
                                            ->label('Cargo/Função'),
                                    ]),

                                Forms\Components\Textarea::make('testimony')
                                    ->requiredWith([
                                        'avatar',
                                        'name',
                                        'job_position',
                                    ])
                                    ->maxLength(250)
                                    ->rows(3)
                                    ->label('Depoimento'),
                            ]),
                    ]),

                Schemas\Components\Section::make('Case de Sucesso')
                    // ->aside()
                    ->schema([
                        WebpImageUpload::make('cover')
                            // ->required(fn($operation) => $operation === 'create')
                            // ->columnSpanFull()
                            ->directory('cases/covers')
                            ->imageEditor()
                            ->imageEditorMode(2)
                            // ->imageEditorEmptyFillColor('#000000')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('8:3')
                            ->imageEditorViewportWidth(900)
                            ->imageEditorViewportHeight(338)
                            ->imageResizeTargetWidth('900')
                            ->imageResizeTargetHeight('338')
                            ->optimize('webp')
                            ->hint(new HtmlString('<small>Min. 900px de largura</small>'))
                            ->label('Capa da Página do Case'),

                        Schemas\Components\Grid::make()
                            ->columns([
                                'sm' => 2,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->maxLength(120)
                                    ->label('Título do Case'),

                                Forms\Components\TextInput::make('slug')
                                    // ->unique()
                                    ->maxLength(125)
                                    ->default(null)
                                    ->hint(new HtmlString('<small>Deixe vazio pra gerar automaticamente</small>'))
                                    // ->hintIcon('heroicon-s-question-mark-circle')
                                    ->label('Slug de Acesso'),
                            ]),

                        Forms\Components\Textarea::make('embed_video')
                            ->columnSpanFull()
                            ->maxLength(1000)
                            ->default(null)
                            ->label('Embed Video Code'),

                        Forms\Components\Textarea::make('cta')
                            ->columnSpanFull()
                            ->maxLength(191)
                            ->default(null)
                            ->label('Resumo/Headline'),

                        Forms\Components\MarkdownEditor::make('content')
                            ->columnSpanFull()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'heading',
                                // 'link',
                                'table',
                            ])
                            ->label('Detalhamento'),
                    ]),

                // Schemas\Components\Group::make()
                //     ->columns([
                //         'default' => 1,
                //         'md' => 2,
                //     ])
                //     ->columnSpan([
                //         'md' => 2,
                //         'lg' => 6,
                //     ])
                //     ->schema([]),
            ]);
    }
}
