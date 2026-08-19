<?php

namespace App\Filament\Dash\Resources;

use App\Filament\Dash\Resources\BlogCategoryResource\Actions\DeleteTasksTrait;
use App\Filament\Dash\Resources\BlogCategoryResource\Pages;
use App\Models\BlogCategory;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class BlogCategoryResource extends Resource
{
    use DeleteTasksTrait;

    protected static ?string $model = BlogCategory::class;

    protected static ?string $slug = 'blog-categories';

    protected static string|\UnitEnum|null $navigationGroup = 'Publicações do Blog';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationLabel = 'Categorias'; // Para Menus e para o Spotlight

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Categoria do Blog';

    protected static ?string $pluralModelLabel = 'Categorias';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function form(Schema $schema): Schema
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

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            // ->recordClasses(fn(BlogCategory $record) => match ($record->trashed()) {
            //     false => '',
            //     true => '!bg-danger-50 dark:!bg-danger-950',
            //     default => '',
            // })
            ->defaultSort('title', 'ASC')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label('Nome da Categoria'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL de Acesso'),

                Tables\Columns\TextColumn::make('articles_count')
                    ->sortable()
                    ->label('Artigos')
                    ->counts('articles')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criação'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Atualização'),
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
            'index' => Pages\ListBlogCategories::route('/'),
            'create' => Pages\CreateBlogCategory::route('/create'),
            'view' => Pages\ViewBlogCategory::route('/{record}'),
            'edit' => Pages\EditBlogCategory::route('/{record}/edit'),
        ];
    }
}
