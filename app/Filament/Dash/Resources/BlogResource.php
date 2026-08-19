<?php

namespace App\Filament\Dash\Resources;

use App\Enums\ResourceVisibilityEnum;
use App\Filament\Dash\Resources\BlogResource\Actions\DeleteTasksTrait;
use App\Filament\Dash\Resources\BlogResource\Pages;
use App\Filament\Forms\Components\WebpImageUpload;
use App\Forms\MakeSlugStringTrait;
use App\Models\Blog;
use App\Models\BlogCategory;
use Filament\Actions\Action;
use Filament\Actions\Action as GlobalSearchAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class BlogResource extends Resource
{
    use DeleteTasksTrait;
    use MakeSlugStringTrait;

    protected static ?string $model = Blog::class;

    protected static ?string $slug = 'blog-articles';

    protected static string|\UnitEnum|null $navigationGroup = 'Publicações do Blog';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Artigos'; // Para Menus e para o Spotlight

    protected static ?int $navigationSort = 0;

    protected static ?string $modelLabel = 'Artigo do Blog';

    protected static ?string $pluralModelLabel = 'Artigos';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    protected static int $globalSearchResultsLimit = 8;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'cta', 'content'];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            GlobalSearchAction::make('Ver')
                ->url(static::getUrl('view', ['record' => $record])),
            GlobalSearchAction::make('Editar')
                ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }

    public static function form(Schema $schema): Schema
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
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('3:1')
                                    // ->imageEditorViewportWidth(900)
                                    // ->imageEditorViewportHeight(338)
                                    ->imageResizeTargetWidth('405')
                                    ->imageResizeTargetHeight('135')
                                    ->optimize('webp')
                                    ->label('Thumbnail'),

                                WebpImageUpload::make('cover')
                                    ->required()
                                    // ->columnSpanFull()
                                    ->directory('blog/covers')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('3:1')
                                    // ->imageEditorViewportWidth(900)
                                    // ->imageEditorViewportHeight(338)
                                    ->imageResizeTargetWidth('1305')
                                    ->imageResizeTargetHeight('435')
                                    ->optimize('webp')
                                    ->label('Capa Principal'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', 'DESC')
            ->recordClasses(
                fn (Blog $record): string => match (true) {
                    // $record->trashed() => '!bg-danger-100 dark:!bg-danger-950',
                    $record->visibility == ResourceVisibilityEnum::Private => '!bg-warning-50 dark:!bg-warning-950',
                    $record->visibility == ResourceVisibilityEnum::Unlisted => '!bg-success-50 dark:!bg-success-950',
                    default => '',
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->label('Autor')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('visibility')
                    ->badge()
                    ->label('Visibilidade'),

                Tables\Columns\ImageColumn::make('thumb')
                    ->sortable(false)
                    ->label('Capa'),

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->label('Titulo/URL')
                    ->searchable()
                    ->limit(42)
                    ->description(
                        description: fn (Blog $record): string => str()->limit($record->slug, 40),
                        position: 'bellow'
                    ),

                Tables\Columns\TextColumn::make('cta')
                    ->label('CTA')
                    ->limit(42),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->label('Criação')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Atualização')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'view' => Pages\ViewBlog::route('/{record}'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
