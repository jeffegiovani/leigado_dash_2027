<?php

namespace App\Filament\Dash\Resources\Blogs\Schemas;

use App\Enums\ResourceVisibilityEnum;
use App\Filament\Forms\Components\WebpImageUpload;
use App\Forms\MakeSlugStringTrait;
use App\Models\BlogCategory;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class BlogForm
{
    use MakeSlugStringTrait;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->columnSpanFull()
                    ->columns([
                        'md' => 5,
                    ])
                    ->schema([
                        Schemas\Components\Group::make()
                            ->columnSpan([
                                'md' => 3,
                                // '2xl' => 4,
                            ])
                            ->columns(1)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(120)
                                    ->columnSpanFull()
                                    ->label('Titulo do Artigo'),

                                Forms\Components\TextInput::make('slug')
                                    // ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(125)
                                    ->visibleOn('edit')
                                    ->columnSpanFull()
                                    ->hint(new HtmlString('<small>Deixe vazio para gerar automaticamente</small>'))
                                    ->label('Url de Acesso'),

                                Forms\Components\Placeholder::make('divider')
                                    // ->label('Divider')
                                    ->visibleOn('edit')
                                    ->content(fn ($record) => new HtmlString('<div class="w-full h-px bg-gray-200 dark:bg-gray-800"></div>'))
                                    ->hiddenLabel()
                                    ->columnSpanFull()
                                    ->dehydrated(false),

                                Forms\Components\Textarea::make('cta')
                                    ->required()
                                    ->columnSpanFull()
                                    ->label('Resumo/Headline'),

                                // Forms\Components\MarkdownEditor::make('content')
                                //     ->required()
                                //     ->disableToolbarButtons([
                                //         'attachFiles',
                                //         'codeBlock',
                                //         'heading',
                                //         // 'link',
                                //         'table',
                                //     ])
                                //     ->columnSpanFull()
                                //     ->label('Conteúdo'),

                                Forms\Components\RichEditor::make('content')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsVisibility('public')
                                    ->fileAttachmentsDirectory('blog/contents')
                                    ->required()
                                    ->columnSpanFull()
                                    ->label('Conteúdo'),
                            ]),

                        Schemas\Components\Group::make()
                            ->columnSpan([
                                'md' => 2,
                                // '2xl' => 1,
                            ])
                            ->columns(1)
                            ->schema([
                                Forms\Components\ToggleButtons::make('visibility')
                                    ->options(ResourceVisibilityEnum::class)
                                    // ->hiddenLabel()
                                    ->required()
                                    ->default(ResourceVisibilityEnum::Public)
                                    // ->grouped()
                                    ->inline()
                                    // ->hint(fn() => view('forms.resource-visibility-hint-helper'))
                                    ->label('Visibilidade'),

                                Forms\Components\Select::make('categories')
                                    ->relationship(
                                        name: 'categories',
                                        titleAttribute: 'title',
                                        //                                        modifyQueryUsing: fn(Builder $query) => $query->limit(6),
                                    )
                                    ->preload()
                                    ->required()
                                    ->multiple()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('category_title')
                                            ->required()
                                            ->maxLength(120)
                                            ->minLength(2)
                                            ->label('Categoria'),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        // $slug = $data['slug'] ?? $data['category_title'];

                                        // $slug = str($slug)
                                        //     ->slug('-', 'pt_BR', ['@' => '-'])
                                        //     ->limit(120, '');

                                        // $checkIsUniqueSlug = BlogCategory::query()
                                        //     ->where('slug', $slug)
                                        //     ->get();

                                        // if ($checkIsUniqueSlug->isNotEmpty()) {
                                        //     $slug .= '-' . uniqid();
                                        // };

                                        $slug = self::makeSlugString(
                                            fullTitle: $data['category_title'],
                                            modelFullyClassName: BlogCategory::class,
                                            slug: $data['category_slug'] ?? $data['category_title'],
                                            limitChars: 120,
                                        );

                                        $data = [
                                            'title' => $data['category_title'],
                                            'slug' => $slug,
                                        ];

                                        return BlogCategory::query()->create($data)->getKey();
                                    })
                                    ->createOptionAction(
                                        function (Action $action) {
                                            $action->modalWidth('xl')
                                                ->modalSubmitAction(function (Action $action) {
                                                    $action->keyBindings(['enter'])->label('Criar e Usar Categoria');
                                                });
                                        }
                                    )
                                    // ->columnSpanFull()
                                    ->label('Categoria'),

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
                                    ->label('Autor'),

                                WebpImageUpload::make('thumb')
                                    ->required()
                                    // ->columnSpanFull()
                                    ->directory('blog/thumbs')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->automaticallyResizeImagesMode('cover')
                                    ->imageAspectRatio('3:1')
                                    ->automaticallyCropImagesToAspectRatio()
                                    // ->imageEditorViewportWidth(900)
                                    // ->imageEditorViewportHeight(338)
                                    ->automaticallyResizeImagesToWidth('405')
                                    ->automaticallyResizeImagesToHeight('135')
                                    ->optimize('webp')
                                    ->label('Thumbnail'),

                                WebpImageUpload::make('cover')
                                    ->required()
                                    // ->columnSpanFull()
                                    ->directory('blog/covers')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->automaticallyResizeImagesMode('cover')
                                    ->imageAspectRatio('3:1')
                                    ->automaticallyCropImagesToAspectRatio()
                                    // ->imageEditorViewportWidth(900)
                                    // ->imageEditorViewportHeight(338)
                                    ->automaticallyResizeImagesToWidth('1305')
                                    ->automaticallyResizeImagesToHeight('435')
                                    ->optimize('webp')
                                    ->label('Capa Principal'),
                            ]),
                    ]),
            ]);
    }
}
