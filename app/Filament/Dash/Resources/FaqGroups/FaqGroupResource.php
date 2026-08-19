<?php

namespace App\Filament\Dash\Resources\FaqGroups;

use App\Filament\Dash\Resources\FaqGroups\Schemas\FaqGroupForm;
use App\Filament\Dash\Resources\FaqGroups\Tables\FaqGroupsTable;
use App\Models\FaqGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FaqGroupResource extends Resource
{
    protected static ?string $model = FaqGroup::class;

    protected static ?string $slug = 'faq-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Perguntas Frequentes';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Grupos de Respostas'; // Para Menus e para o Spotlight

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Grupo de Respostas';

    protected static ?string $pluralModelLabel = 'Grupos de Respostas';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function form(Schema $schema): Schema
    {
        return FaqGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqGroupsTable::configure($table);
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
            'index' => Pages\ListFaqGroups::route('/'),
            'create' => Pages\CreateFaqGroup::route('/create'),
            'view' => Pages\ViewFaqGroup::route('/{record}'),
            'edit' => Pages\EditFaqGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([]);
    }
}
