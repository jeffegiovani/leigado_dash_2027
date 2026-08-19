<?php

namespace App\Filament\Dash\Resources\BlogCategories;

use App\Filament\Dash\Resources\BlogCategories\Schemas\BlogCategoryForm;
use App\Filament\Dash\Resources\BlogCategories\Tables\BlogCategoriesTable;
use App\Models\BlogCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class BlogCategoryResource extends Resource
{
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
        return BlogCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogCategoriesTable::configure($table);
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
