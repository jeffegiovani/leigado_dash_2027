<?php

namespace App\Filament\Dash\Resources\Coupons\Schemas;

use App\Enums\ResourceVisibilityEnum;
use App\Enums\SegmentsEnum;
use App\Filament\Forms\Components\WebpImageUpload;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                        'lg' => 8,
                    ])
                    ->schema([
                        Schemas\Components\Group::make()
                            ->columns(1)
                            ->columnSpan([
                                'default' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Schemas\Components\Section::make()
                                    ->columnSpan([
                                        'default' => 1,
                                    ])
                                    ->heading(null)
                                    ->hiddenLabel()
                                    ->schema([
                                        Forms\Components\CheckboxList::make('segments')
                                            ->required()
                                            ->options(SegmentsEnum::class)
                                            // ->columnSpanFull()
                                            ->bulkToggleable()
                                            // ->columns(3)
                                            ->label('Segmentos Contemplados'),
                                    ]),

                                Forms\Components\Select::make('author_id')
                                    ->required()
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
                                    ->selectablePlaceholder(false)
                                    ->label('Responsável pela Campanha'),

                                WebpImageUpload::make('avatar')
                                    // ->required(fn($operation) => $operation === 'create')
                                    ->required()
                                    // ->columnSpanFull()
                                    ->directory('coupons/avatars')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->automaticallyResizeImagesMode('cover')
                                    // ->imageAspectRatio('1:1')
                                    // ->automaticallyCropImagesToAspectRatio()
                                    // ->imageEditorViewportWidth(1280)
                                    // ->imageEditorViewportHeight(720)
                                    ->automaticallyResizeImagesToWidth('160')
                                    ->automaticallyResizeImagesToHeight('160')
                                    ->imageEditorAspectRatioOptions([
                                        null,
                                    ])
                                    ->optimize('webp')
                                    ->label('Avatar da Campanha'),

                                WebpImageUpload::make('cover')
                                    // ->required(fn($operation) => $operation === 'create')
                                    // ->columnSpanFull()
                                    ->directory('coupons/covers')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->automaticallyResizeImagesMode('cover')
                                    ->imageAspectRatio('8:3')
                                    ->automaticallyCropImagesToAspectRatio()
                                    ->imageEditorViewportWidth(900)
                                    ->imageEditorViewportHeight(338)
                                    ->automaticallyResizeImagesToWidth('900')
                                    ->automaticallyResizeImagesToHeight('338')
                                    ->optimize('webp')
                                    ->label('Capa da Campanha'),
                            ]),

                        Schemas\Components\Group::make()
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->columnSpan([
                                'md' => 2,
                                'lg' => 6,
                            ])
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
                                    ->label('Visibilidade'),

                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(120)
                                    ->columnSpanFull()
                                    ->label('Título da Campanha'),

                                Forms\Components\TextInput::make('slug')
                                    // ->unique()
                                    ->columnSpanFull()
                                    ->maxLength(125)
                                    ->default(null)
                                    ->hint(new HtmlString('<small>Deixe vazio pra gerar automaticamente</small>'))
                                    // ->hintIcon('heroicon-s-question-mark-circle')
                                    ->label('Slug de Acesso'),

                                Forms\Components\TextInput::make('partner')
                                    ->required()
                                    ->maxLength(80)
                                    ->label('Parceiro de Negócios'),

                                Forms\Components\TextInput::make('offer_headline')
                                    ->required()
                                    ->maxLength(20)
                                    ->label('Headline de 20 chars'),

                                Forms\Components\Textarea::make('cta')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(191)
                                    ->default(null)
                                    ->label('Chamada'),

                                Forms\Components\MarkdownEditor::make('content')
                                    ->required()
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
                    ]),

            ]);
    }
}
