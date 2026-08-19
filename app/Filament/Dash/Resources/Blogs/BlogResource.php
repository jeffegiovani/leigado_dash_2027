<?php

namespace App\Filament\Dash\Resources\Blogs;

use App\Filament\Dash\Resources\Blogs\Schemas\BlogForm;
use App\Filament\Dash\Resources\Blogs\Tables\BlogsTable;
use App\Models\Blog;
use Filament\Actions\Action as GlobalSearchAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BlogResource extends Resource
{
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
        return BlogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogsTable::configure($table);
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
